<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

// Coleta os dados do formulário
$nome_aula = isset($_POST["nome_aula"]) ? trim($_POST["nome_aula"]) : null; 
$dia_aula = isset($_POST["dia_aula"]) ? trim($_POST["dia_aula"]) : null; 
$horario_aula = isset($_POST["horario_aula"]) ? trim($_POST["horario_aula"]) : null;
$professor_aula = isset($_POST["professor_aula"]) ? trim($_POST["professor_aula"]) : null; 
$local_aula = isset($_POST["local_aula"]) ? trim($_POST["local_aula"]) : null; 

// Verifica se todos os dados foram preenchidos
if (empty($nome_aula) || empty($dia_aula) || empty($horario_aula) || empty($professor_aula) || empty($local_aula)) {
    // Log de tentativa com dados incompletos
    Log::registrar(
        "TENTATIVA_CADASTRO_AULA_INCOMPLETA",
        "criar_aula",
        null,
        null,
        [
            "nome_aula" => $nome_aula,
            "dia_aula" => $dia_aula,
            "horario_aula" => $horario_aula,
            "professor_aula" => $professor_aula,
            "local_aula" => $local_aula
        ]
    );
    
    header("Location: index.php?error=incomplete_data");
    exit;
}

try {
    $sql = "INSERT INTO `criar_aula` 
        (
            nome_aula,  
            dia_aula, 
            horario_aula, 
            professor_aula,
            local_aula 
        ) 
        VALUES 
        (
            :nome_aula,
            :dia_aula,
            :horario_aula,
            :professor_aula,
            :local_aula
        )";

    $executar = Db::conexao()->prepare($sql);

    $executar->bindValue(":nome_aula", $nome_aula, PDO::PARAM_STR);
    $executar->bindValue(":dia_aula", $dia_aula, PDO::PARAM_STR);
    $executar->bindValue(":horario_aula", $horario_aula, PDO::PARAM_STR);
    $executar->bindValue(":professor_aula", $professor_aula, PDO::PARAM_STR);
    $executar->bindValue(":local_aula", $local_aula, PDO::PARAM_STR);

    if (!$executar->execute()) {
        throw new Exception("Falha ao executar a inserção");
    }

    $aula_id = Db::conexao()->lastInsertId();

    // Log de cadastro bem-sucedido
    $logResult = Log::registrar(
        "CADASTRO_AULA",
        "criar_aula",
        $aula_id,
        null,
        [
            "nome_aula" => $nome_aula,
            "dia_aula" => $dia_aula,
            "horario_aula" => $horario_aula,
            "professor_aula" => $professor_aula,
            "local_aula" => $local_aula
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de cadastro de aula");
    }

    header("Location: index.php?success=created&id=".$aula_id);
    exit;

} catch (Exception $e) {
    // Log de erro no cadastro
    $logResult = Log::registrar(
        "ERRO_CADASTRO_AULA",
        "criar_aula",
        null,
        null,
        [
            "erro" => $e->getMessage(),
            "dados_tentativa" => [
                "nome_aula" => $nome_aula,
                "dia_aula" => $dia_aula,
                "horario_aula" => $horario_aula,
                "professor_aula" => $professor_aula,
                "local_aula" => $local_aula
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro no cadastro de aula: " . $e->getMessage());
    }

    header("Location: index.php?error=database_error");
    exit;
}