<?php
header('Content-Type: application/json');

include_once './conexao.php';

$query = "SELECT id, nome FROM aluno WHERE ativo = 1 ORDER BY nome ASC";
$result = $conn->prepare($query);
$result->execute();

if ($result->rowCount() > 0) {
    $clientes = $result->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => true, 'dados' => $clientes]);
} else {
    echo json_encode(['status' => false, 'msg' => 'Nenhum cliente encontrado.']);
}
?>
