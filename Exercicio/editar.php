<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php"); // Inclui a classe de Log

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$nome = isset($_POST["nome"]) ? $_POST["nome"] : NULL;
$tipo_exercicio = isset($_POST["tipo_exercicio"]) ? $_POST["tipo_exercicio"] : NULL;
$grupo_muscular = isset($_POST["grupo_muscular"]) ? $_POST["grupo_muscular"] : NULL;

// Validação básica
if (!$id || !$nome || !$tipo_exercicio || !$grupo_muscular) {
    // Log de tentativa com dados incompletos
    Log::registrar(
        "TENTATIVA_EDICAO_EXERCICIO_INCOMPLETO",
        "exercicio",
        $id,
        null,
        [
            "id" => $id,
            "nome" => $nome,
            "tipo_exercicio" => $tipo_exercicio,
            "grupo_muscular" => $grupo_muscular
        ]
    );
    
    echo "<script>alert('Todos os campos são obrigatórios!'); history.back();</script>";
    exit;
}

// Obter dados atuais para log
try {
    $consultaAtual = Db::conexao()->prepare("SELECT * FROM exercicio WHERE id = :id");
    $consultaAtual->bindValue(":id", $id, PDO::PARAM_INT);
    $consultaAtual->execute();
    $dadosAntigos = $consultaAtual->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Erro ao buscar dados antigos do exercício: " . $e->getMessage());
    $dadosAntigos = null;
}

try {
    $sql = "UPDATE `exercicio` 
            SET
               `nome` = :nome, 
                `tipo_exercicio` = :tipo_exercicio, 
                `grupo_muscular` = :grupo_muscular
            WHERE 
                `id` = :id";

    $executar = Db::conexao()->prepare($sql);

    $executar->bindValue(":id", $id, PDO::PARAM_INT);
    $executar->bindValue(":nome", $nome, PDO::PARAM_STR);
    $executar->bindValue(":tipo_exercicio", $tipo_exercicio, PDO::PARAM_STR);
    $executar->bindValue(":grupo_muscular", $grupo_muscular, PDO::PARAM_STR);

    if (!$executar->execute()) {
        throw new Exception("Falha na execução da query de atualização");
    }

    // Dados novos para log
    $dadosNovos = [
        'nome' => $nome,
        'tipo_exercicio' => $tipo_exercicio,
        'grupo_muscular' => $grupo_muscular
    ];

    // Registrar log de edição
    $logResult = Log::registrar(
        "EDICAO_EXERCICIO",
        "exercicio",
        $id,
        $dadosAntigos,
        $dadosNovos
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de edição de exercício");
    }

    header("Location: index.php");
    exit;

} catch (Exception $e) {
    // Registrar log de erro
    $logResult = Log::registrar(
        "ERRO_EDICAO_EXERCICIO",
        "exercicio",
        $id,
        null,
        [
            'erro' => $e->getMessage(),
            'dados_tentativa' => [
                'id' => $id,
                'nome' => $nome,
                'tipo_exercicio' => $tipo_exercicio,
                'grupo_muscular' => $grupo_muscular
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro na edição: " . $e->getMessage());
    }

    echo "<script>alert('Erro ao editar exercício: " . addslashes($e->getMessage()) . "'); history.back();</script>";
    exit;
}