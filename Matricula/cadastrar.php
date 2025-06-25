<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php"); // Adicionei a inclusão da classe Log

$nome = $_POST["nome"] ?? NULL;
$data_nascimento = $_POST["data_nascimento"] ?? NULL;
$cpf = $_POST["cpf"] ?? NULL;
$telefone = $_POST["telefone"] ?? NULL;
$endereco = $_POST["endereco"] ?? NULL;
$frequencia = $_POST["frequencia"] ?? NULL;
$objetivo = $_POST["objetivo"] ?? NULL;
$data_matricula = $_POST["data_matricula"] ?? NULL;

// Validação de cpf 
if (strlen($cpf) !== 11) {
    // Log de tentativa com CPF inválido
    Log::registrar(
        "TENTATIVA_CADASTRO_CPF_INVALIDO",
        "aluno",
        null,
        null,
        ["cpf" => $cpf, "tamanho" => strlen($cpf)]
    );
    
    echo "<script>alert('CPF deve conter exatamente 11 números.'); history.back();</script>";
    exit;
}

// Validação de telefone 
if (strlen($telefone) !== 11) {
    // Log de tentativa com telefone inválido
    Log::registrar(
        "TENTATIVA_CADASTRO_TELEFONE_INVALIDO",
        "aluno",
        null,
        null,
        ["telefone" => $telefone, "tamanho" => strlen($telefone)]
    );
    
    echo "<script>alert('Telefone deve conter exatamente 11 números.'); history.back();</script>";
    exit;
}

// Verificar duplicidade
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
    // Log de tentativa com CPF duplicado
    Log::registrar(
        "TENTATIVA_CADASTRO_CPF_DUPLICADO",
        "aluno",
        null,
        null,
        ["cpf" => $cpf, "nome" => $nome]
    );
    
    echo "<script>alert('CPF já cadastrado!'); history.back();</script>";
    exit;
}

$sql = ("INSERT INTO aluno 
    (nome, data_nascimento, cpf, telefone, endereco, frequencia, objetivo, data_matricula)
    VALUES 
    (:nome, :data_nascimento, :cpf, :telefone, :endereco, :frequencia, :objetivo, :data_matricula)");

$executar = Db::conexao()->prepare($sql);

$executar->bindValue(":nome", $nome, PDO::PARAM_STR);
$executar->bindValue(":data_nascimento", $data_nascimento, PDO::PARAM_STR);
$executar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$executar->bindValue(":telefone", $telefone, PDO::PARAM_STR);
$executar->bindValue(":endereco", $endereco, PDO::PARAM_STR);
$executar->bindValue(":frequencia", $frequencia, PDO::PARAM_INT);
$executar->bindValue(":objetivo", $objetivo, PDO::PARAM_STR);
$executar->bindValue(":data_matricula", $data_matricula, PDO::PARAM_STR);

if ($executar->execute()) {
    $ultimoId = Db::conexao()->lastInsertId();
    
    // Log de cadastro bem-sucedido
    Log::registrar(
        "CADASTRO_ALUNO",
        "aluno",
        $ultimoId,
        null,
        [
            "nome" => $nome,
            "cpf" => $cpf,
            "data_nascimento" => $data_nascimento,
            "telefone" => $telefone,
            "data_matricula" => $data_matricula
        ]
    );
    
    header("Location: index.php");
    exit;
} else {
    // Log de erro no cadastro
    Log::registrar(
        "ERRO_CADASTRO_ALUNO",
        "aluno",
        null,
        null,
        [
            "erro" => "Falha na execução da query",
            "dados" => [
                "nome" => $nome,
                "cpf" => $cpf
            ]
        ]
    );
    
    echo "<script>alert('Erro ao cadastrar aluno.'); history.back();</script>";
    exit;
}