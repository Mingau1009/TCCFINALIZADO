<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php"); // Inclua a classe Log

$nome = isset($_POST["nome"]) ? $_POST["nome"] : NULL;
$data_nascimento = isset($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : NULL;
$cpf = $_POST["cpf"] ?? NULL;
$telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : NULL;
$endereco = isset($_POST["endereco"]) ? $_POST["endereco"] : NULL;
$turno_disponivel = isset($_POST["turno_disponivel"]) ? $_POST["turno_disponivel"] : NULL;
$data_matricula = isset($_POST["data_matricula"]) ? $_POST["data_matricula"] : NULL;

// Validações (mantidas como estão)
if (strlen($cpf) !== 11) {
    echo "<script>alert('CPF deve conter exatamente 11 números.'); history.back();</script>";
    exit;
}

if (strlen($telefone) !== 11) {
    echo "<script>alert('Telefone deve conter exatamente 11 números.'); history.back();</script>";
    exit;
}

// Verificar duplicidade (mantido como está)
$verificar = Db::conexao()->prepare("
    SELECT COUNT(*) FROM (
        SELECT cpf FROM aluno WHERE cpf = :cpf
        UNION
        SELECT cpf FROM funcionario WHERE cpf = :cpf
    ) AS resultado
");
$verificar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$verificar->execute();
$total = $verificar->fetchColumn();

if ($total > 0) {
    echo "<script>alert('CPF já cadastrado!'); history.back();</script>";
    exit;
}

// Preparar dados para log
$dadosNovos = [
    'nome' => $nome,
    'data_nascimento' => $data_nascimento,
    'cpf' => $cpf,
    'telefone' => $telefone,
    'endereco' => $endereco,
    'turno_disponivel' => $turno_disponivel,
    'data_matricula' => $data_matricula
];

$sql = "INSERT INTO `funcionario` 
    (
        `nome`, `data_nascimento`, `cpf`, `telefone`, 
        `endereco`, `turno_disponivel`, `data_matricula`
    ) 
    VALUES 
    (
        :nome, :data_nascimento, :cpf, :telefone,
        :endereco, :turno_disponivel, :data_matricula
    )";

$executar = Db::conexao()->prepare($sql);

// Bind dos valores (mantido como está)
$executar->bindValue(":nome", $nome, PDO::PARAM_STR);
$executar->bindValue(":data_nascimento", $data_nascimento, PDO::PARAM_STR);
$executar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$executar->bindValue(":telefone", $telefone, PDO::PARAM_STR);
$executar->bindValue(":endereco", $endereco, PDO::PARAM_STR);
$executar->bindValue(":turno_disponivel", $turno_disponivel, PDO::PARAM_STR);
$executar->bindValue(":data_matricula", $data_matricula, PDO::PARAM_STR);

if ($executar->execute()) {
    // Registrar log após cadastro bem-sucedido
    $ultimoId = Db::conexao()->lastInsertId();
    Log::registrar(
        "CADASTRO_FUNCIONARIO", 
        "funcionario", 
        $ultimoId, 
        null, 
        $dadosNovos
    );
    
    header("Location: index.php");
    exit;
} else {
    echo "<script>alert('Erro ao cadastrar funcionário.'); history.back();</script>";
    exit;
}