<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

$id = isset($_GET["id"]) ? $_GET["id"] : null;

if (!$id) {
    http_response_code(400);
    echo json_encode(["erro" => "ID é obrigatório"]);
    exit;
}

try {
    $conexao = Db::conexao();
    $conexao->beginTransaction();

    // Primeiro, obtemos os alunos associados para registrar no log
    $stmtGetAlunos = $conexao->prepare("SELECT aluno_id FROM evento_aluno WHERE evento_id = :id");
    $stmtGetAlunos->bindValue(":id", $id, PDO::PARAM_INT);
    $stmtGetAlunos->execute();
    $alunos = $stmtGetAlunos->fetchAll(PDO::FETCH_COLUMN);

    // Dados anteriores para o log
    $dados_anteriores = [
        'evento_id' => $id,
        'alunos' => $alunos
    ];

    // Excluir a relação aluno-evento
    $stmtExcluir = $conexao->prepare("DELETE FROM evento_aluno WHERE evento_id = :id");
    $stmtExcluir->bindValue(":id", $id, PDO::PARAM_INT); 
    $stmtExcluir->execute();

    // Registrar a ação no log
    Log::registrarAulaAluno('EXCLUIR_AULA_ALUNOS', $id, [], $dados_anteriores);

    $conexao->commit();

    echo json_encode(["mensagem" => "Alunos removidos da aula com sucesso"]);

} catch (Exception $e) {
    $conexao->rollBack();
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao excluir: " . $e->getMessage()]);
}
?>