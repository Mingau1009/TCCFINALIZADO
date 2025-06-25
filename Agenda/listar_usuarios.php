<?php
include_once './conexao.php';

$profissional = filter_input(INPUT_GET, 'profissional', FILTER_SANITIZE_STRING);

if($profissional == 'S') {
    // Listar professores (funcionários)
    $query = "SELECT id, nome as name, telefone as phone FROM funcionario WHERE ativo = 1 ORDER BY nome ASC";
} else {
    // Listar alunos
    $query = "SELECT id, nome as name, telefone as phone FROM aluno WHERE ativo = 1 ORDER BY nome ASC";
}

$result = $conn->prepare($query);
$result->execute();

if(($result) && ($result->rowCount() != 0)) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $dados[] = $row;
    }
    $retorna = ['status' => true, 'dados' => $dados];
} else {
    $retorna = ['status' => false, 'msg' => 'Nenhum registro encontrado!'];
}

echo json_encode($retorna);