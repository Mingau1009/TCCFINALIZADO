<?php

include("../Classe/Conexao.php");

$id = $_POST["id"] ?? NULL;
$nome = $_POST["nome"] ?? NULL;
$data_nascimento = $_POST["data_nascimento"] ?? NULL;
$cpf = $_POST["cpf"] ?? NULL;
$telefone = $_POST["telefone"] ?? NULL;
$endereco = $_POST["endereco"] ?? NULL;
$frequencia = $_POST["frequencia"] ?? NULL;
$objetivo = $_POST["objetivo"] ?? NULL;
$data_matricula = $_POST["data_matricula"] ?? NULL;
$ativo = $_POST["ativo"] ?? 1;

// Validação de CPF
if (strlen($cpf) !== 11) {
    echo "<script>alert('CPF deve conter exatamente 11 dígitos.'); history.back();</script>";
    exit;
}

// Validação de telefone
if (strlen($telefone) !== 11) {
    echo "<script>alert('Telefone deve conter exatamente 11 dígitos.'); history.back();</script>";
    exit;
}

$alunoAtual = Db::conexao()->prepare("SELECT cpf FROM aluno WHERE id = :id");
$alunoAtual->bindParam(':id', $id, PDO::PARAM_INT);
$alunoAtual->execute();
$cpfAtual = $alunoAtual->fetchColumn();

// Verificar duplicidade de CPF
$verificar = Db::conexao()->prepare("
    SELECT COUNT(*) 
    FROM (
        SELECT cpf FROM aluno WHERE cpf = :cpf AND id != :id
        UNION
        SELECT cpf FROM funcionario WHERE cpf = :cpf
    ) AS resultado
");
$verificar->bindParam(':cpf', $cpf);
$verificar->bindParam(':id', $id, PDO::PARAM_INT);
$verificar->execute();
$total = $verificar->fetchColumn();

if ($total > 0) {
    echo "<script>alert('CPF já cadastrado em outro usuário.'); history.back();</script>";
    exit;
}
$alunoAtual = Db::conexao()->prepare("SELECT * FROM aluno WHERE id = :id");
$alunoAtual->bindParam(':id', $id, PDO::PARAM_INT);
$alunoAtual->execute();
$dadosAnteriores = $alunoAtual->fetch(PDO::FETCH_ASSOC);

// Atualizar dados
$sql = "
    UPDATE aluno 
    SET
        nome = :nome, 
        data_nascimento = :data_nascimento, 
        cpf = :cpf,
        telefone = :telefone, 
        endereco = :endereco, 
        frequencia = :frequencia, 
        objetivo = :objetivo, 
        data_matricula = :data_matricula,
        ativo = :ativo
    WHERE
     id = :id
";

$executar = Db::conexao()->prepare($sql);

$executar->bindValue(":id", $id, PDO::PARAM_INT);
$executar->bindValue(":nome", $nome, PDO::PARAM_STR);
$executar->bindValue(":data_nascimento", $data_nascimento, PDO::PARAM_STR);
$executar->bindValue(":cpf", $cpf, PDO::PARAM_STR);
$executar->bindValue(":telefone", $telefone, PDO::PARAM_STR);
$executar->bindValue(":endereco", $endereco, PDO::PARAM_STR);
$executar->bindValue(":frequencia", $frequencia, PDO::PARAM_INT);
$executar->bindValue(":objetivo", $objetivo, PDO::PARAM_STR);
$executar->bindValue(":data_matricula", $data_matricula, PDO::PARAM_STR);
$executar->bindValue(":ativo", $ativo, PDO::PARAM_INT);

$executar->execute();

if ($executar->execute()) {
    // Registrar log
    $dadosNovos = [
        'nome' => $nome,
        'data_nascimento' => $data_nascimento,
        'cpf' => $cpf,
        'telefone' => $telefone,
        'endereco' => $endereco,
        'frequencia' => $frequencia,
        'objetivo' => $objetivo,
        'data_matricula' => $data_matricula,
        'ativo' => $ativo
    ];
    
    include("../Classe/Log.php");
    Log::registrar("EDICAO_ALUNO", "aluno", $id, $dadosAnteriores, $dadosNovos);
    
    header("Location: index.php");
    exit;
} else {
    echo "<script>alert('Erro ao atualizar aluno.'); history.back();</script>";
    exit;
}
