<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

$id = isset($_POST["id"]) && is_numeric($_POST["id"]) ? (int)$_POST["id"] : null;

if ($id === null) {
    // Log de tentativa sem ID
    Log::registrar(
        "TENTATIVA_EDICAO_AULA_SEM_ID",
        "criar_aula",
        null,
        null,
        ["id" => $_POST["id"] ?? null]
    );
    
    die("<script>alert('ID inválido!'); history.back();</script>");
}

// Obter dados atuais para log
try {
    $consultaAtual = Db::conexao()->prepare("SELECT * FROM criar_aula WHERE id = :id");
    $consultaAtual->bindValue(":id", $id, PDO::PARAM_INT);
    $consultaAtual->execute();
    $dadosAntigos = $consultaAtual->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao buscar dados antigos da aula: " . $e->getMessage());
    $dadosAntigos = null;
}

// Validar e coletar dados
$nome_aula = isset($_POST["nome_aula"]) ? trim($_POST["nome_aula"]) : null;
$dia_aula = isset($_POST["dia_aula"]) ? trim($_POST["dia_aula"]) : null;
$horario_aula = isset($_POST["horario_aula"]) ? trim($_POST["horario_aula"]) : null;
$professor_aula = isset($_POST["professor_aula"]) ? trim($_POST["professor_aula"]) : null;
$local_aula = isset($_POST["local_aula"]) ? trim($_POST["local_aula"]) : null;

if (empty($nome_aula) || empty($dia_aula) || empty($horario_aula) || empty($professor_aula) || empty($local_aula)) {
    // Log de tentativa com dados incompletos
    Log::registrar(
        "TENTATIVA_EDICAO_AULA_INCOMPLETA",
        "criar_aula",
        $id,
        null,
        [
            "nome_aula" => $nome_aula,
            "dia_aula" => $dia_aula,
            "horario_aula" => $horario_aula,
            "professor_aula" => $professor_aula,
            "local_aula" => $local_aula
        ]
    );
    
    die("<script>alert('Todos os campos são obrigatórios!'); history.back();</script>");
}

try {
    $sql = "UPDATE `criar_aula` 
            SET 
                `nome_aula` = :nome_aula,
                `dia_aula` = :dia_aula,
                `horario_aula` = :horario_aula,
                `professor_aula` = :professor_aula,
                `local_aula` = :local_aula
            WHERE `id` = :id";

    $conexao = Db::conexao();
    $stmt = $conexao->prepare($sql);

    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->bindValue(":nome_aula", $nome_aula, PDO::PARAM_STR);
    $stmt->bindValue(":dia_aula", $dia_aula, PDO::PARAM_STR);
    $stmt->bindValue(":horario_aula", $horario_aula, PDO::PARAM_STR);
    $stmt->bindValue(":professor_aula", $professor_aula, PDO::PARAM_STR);
    $stmt->bindValue(":local_aula", $local_aula, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        throw new Exception("Erro ao executar a atualização");
    }

    // Dados novos para log
    $dadosNovos = [
        "nome_aula" => $nome_aula,
        "dia_aula" => $dia_aula,
        "horario_aula" => $horario_aula,
        "professor_aula" => $professor_aula,
        "local_aula" => $local_aula
    ];

    // Log de edição bem-sucedida
    $logResult = Log::registrar(
        "EDICAO_AULA",
        "criar_aula",
        $id,
        $dadosAntigos,
        $dadosNovos
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de edição de aula");
    }

    header("Location: index.php?success=updated&id=".$id);
    exit;
    
} catch (Exception $e) {
    // Log de erro na edição
    $logResult = Log::registrar(
        "ERRO_EDICAO_AULA",
        "criar_aula",
        $id,
        null,
        [
            "erro" => $e->getMessage(),
            "dados_tentativa" => [
                "id" => $id,
                "nome_aula" => $nome_aula,
                "dia_aula" => $dia_aula,
                "horario_aula" => $horario_aula,
                "professor_aula" => $professor_aula,
                "local_aula" => $local_aula
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro na edição de aula: " . $e->getMessage());
    }

    die("<script>alert('Erro ao atualizar a aula. Por favor, tente novamente.'); history.back();</script>");
}