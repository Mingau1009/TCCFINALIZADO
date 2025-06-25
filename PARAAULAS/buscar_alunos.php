<?php
include("../Classe/Conexao.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

if (!isset($_POST['evento_id'])) {
    http_response_code(400);
    echo json_encode(["erro" => "ID do evento não fornecido"]);
    exit;
}

try {
    $conexao = Db::conexao();
    $stmt = $conexao->prepare("
        SELECT a.id, a.nome 
        FROM aluno a
        INNER JOIN evento_aluno ea ON a.id = ea.aluno_id
        WHERE ea.evento_id = :evento_id
    ");
    $stmt->bindValue(":evento_id", $_POST['evento_id'], PDO::PARAM_INT);
    $stmt->execute();
    
    $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(["alunos" => $alunos]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao buscar alunos: " . $e->getMessage()]);
}
?>