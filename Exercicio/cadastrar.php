<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php"); // Inclui a classe de Log

$nome = isset($_POST["nome"]) ? $_POST["nome"] : NULL;
$tipo_exercicio = isset($_POST["tipo_exercicio"]) ? $_POST["tipo_exercicio"] : NULL;
$grupo_muscular = isset($_POST["grupo_muscular"]) ? $_POST["grupo_muscular"] : NULL;

// Validação básica
if (!$nome || !$tipo_exercicio || !$grupo_muscular) {
    // Log de tentativa com dados incompletos
    Log::registrar(
        "TENTATIVA_CADASTRO_EXERCICIO_INCOMPLETO",
        "exercicio",
        null,
        null,
        [
            "nome" => $nome,
            "tipo_exercicio" => $tipo_exercicio,
            "grupo_muscular" => $grupo_muscular
        ]
    );
    
    echo "<script>alert('Todos os campos são obrigatórios!'); history.back();</script>";
    exit;
}

try {
    $sql = "INSERT INTO `exercicio` 
            (
                `nome`, 
                `tipo_exercicio`, 
                `grupo_muscular`
            ) 
            VALUES 
            (
                :nome,
                :tipo_exercicio,
                :grupo_muscular
            )";

    $executar = Db::conexao()->prepare($sql);

    $executar->bindValue(":nome", $nome, PDO::PARAM_STR);
    $executar->bindValue(":tipo_exercicio", $tipo_exercicio, PDO::PARAM_STR);
    $executar->bindValue(":grupo_muscular", $grupo_muscular, PDO::PARAM_STR);

    if (!$executar->execute()) {
        throw new Exception("Falha na execução da query de inserção");
    }

    $ultimoId = Db::conexao()->lastInsertId();

    // Dados do novo exercício para log
    $dadosNovos = [
        'nome' => $nome,
        'tipo_exercicio' => $tipo_exercicio,
        'grupo_muscular' => $grupo_muscular
    ];

    // Registrar log de cadastro
    $logResult = Log::registrar(
        "CADASTRO_EXERCICIO",
        "exercicio",
        $ultimoId,
        null,
        $dadosNovos
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de cadastro de exercício");
    }

    header("Location: index.php");
    exit;

} catch (Exception $e) {
    // Registrar log de erro
    $logResult = Log::registrar(
        "ERRO_CADASTRO_EXERCICIO",
        "exercicio",
        null,
        null,
        [
            'erro' => $e->getMessage(),
            'dados_tentativa' => [
                'nome' => $nome,
                'tipo_exercicio' => $tipo_exercicio,
                'grupo_muscular' => $grupo_muscular
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro no cadastro: " . $e->getMessage());
    }

    echo "<script>alert('Erro ao cadastrar exercício: " . addslashes($e->getMessage()) . "'); history.back();</script>";
    exit;
}