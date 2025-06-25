<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php"); // Inclua a classe Log

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$nome = isset($_POST["nome"]) ? $_POST["nome"] : NULL;
$data_nascimento = isset($_POST["data_nascimento"]) ? $_POST["data_nascimento"] : NULL;
$cpf = $_POST["cpf"] ?? NULL;
$telefone = isset($_POST["telefone"]) ? $_POST["telefone"] : NULL;
$endereco = isset($_POST["endereco"]) ? $_POST["endereco"] : NULL;
$turno_disponivel = isset($_POST["turno_disponivel"]) ? $_POST["turno_disponivel"] : NULL;
$data_matricula = isset($_POST["data_matricula"]) ? $_POST["data_matricula"] : NULL;
$ativo = isset($_POST["ativo"]) ? $_POST["ativo"] : 1;

// Validações (mantidas como estão)
if (strlen($cpf) !== 11) {
    echo "<script>alert('CPF deve conter exatamente 11 dígitos.'); history.back();</script>";
    exit;
}

if (strlen($telefone) !== 11) {
    echo "<script>alert('Telefone deve conter exatamente 11 dígitos.'); history.back();</script>";
    exit;
}

// Primeiro, buscar os dados atuais para o log
$funcionarioAtual = Db::conexao()->prepare("SELECT * FROM funcionario WHERE id = :id");
$funcionarioAtual->bindParam(':id', $id, PDO::PARAM_INT);
$funcionarioAtual->execute();
$dadosAnteriores = $funcionarioAtual->fetch(PDO::FETCH_ASSOC);

// Verificar duplicidade (mantido como está)
$verificar = Db::conexao()->prepare("
    SELECT COUNT(*) FROM (
        SELECT cpf FROM aluno WHERE cpf = :cpf
        UNION
        SELECT cpf FROM funcionario WHERE cpf = :cpf AND id != :id
    ) AS resultado
");
$verificar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$verificar->bindValue(":id", $id, PDO::PARAM_INT);
$verificar->execute();
$total = $verificar->fetchColumn();

if ($total > 0) {
    echo "<script>alert('CPF já cadastrado em outro registro.'); history.back();</script>";
    exit;
}

// Preparar novos dados para log
$dadosNovos = [
    'nome' => $nome,
    'data_nascimento' => $data_nascimento,
    'cpf' => $cpf,
    'telefone' => $telefone,
    'endereco' => $endereco,
    'turno_disponivel' => $turno_disponivel,
    'data_matricula' => $data_matricula,
    'ativo' => $ativo
];

$sql = "UPDATE `funcionario`
    SET
        `nome` = :nome, 
        `data_nascimento` = :data_nascimento, 
        `cpf` = :cpf,
        `telefone` = :telefone, 
        `endereco` = :endereco, 
        `turno_disponivel` = :turno_disponivel, 
        `data_matricula` = :data_matricula,
        `ativo` = :ativo
    WHERE
        `id` = :id";

$executar = Db::conexao()->prepare($sql);

// Bind dos valores (mantido como está)
$executar->bindValue(":id", $id, PDO::PARAM_INT);
$executar->bindValue(":nome", $nome, PDO::PARAM_STR);
$executar->bindValue(":data_nascimento", $data_nascimento, PDO::PARAM_STR);
$executar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$executar->bindValue(":telefone", $telefone, PDO::PARAM_STR);
$executar->bindValue(":endereco", $endereco, PDO::PARAM_STR);
$executar->bindValue(":turno_disponivel", $turno_disponivel, PDO::PARAM_STR);
$executar->bindValue(":data_matricula", $data_matricula, PDO::PARAM_STR);
$executar->bindValue(":ativo", $ativo, PDO::PARAM_INT);

if ($executar->execute()) {
    // Registrar log após edição bem-sucedida
    Log::registrar(
        "EDICAO_FUNCIONARIO", 
        "funcionario", 
        $id, 
        $dadosAnteriores, 
        $dadosNovos
    );
    
    header("Location: index.php");
    exit;
} else {
    echo "<script>alert('Erro ao atualizar funcionário.'); history.back();</script>";
    exit;
}