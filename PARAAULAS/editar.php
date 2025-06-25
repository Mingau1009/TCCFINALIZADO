<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($dados['id']) || !isset($dados['alunos']) || !is_array($dados['alunos'])) {
    http_response_code(400);
    echo json_encode(["erro" => "Dados incompletos ou inválidos."]);
    exit;
}

$evento_id = $dados['id'];
$alunos = $dados['alunos'];

try {
    $conexao = Db::conexao();
    $conexao->beginTransaction();

    // Verificar se o evento existe
    $stmtVerifica = $conexao->prepare("SELECT id, nome_aula FROM criar_aula WHERE id = :evento_id");
    $stmtVerifica->bindValue(":evento_id", $evento_id, PDO::PARAM_INT);
    $stmtVerifica->execute();
    
    if (!$stmtVerifica->fetch()) {
        throw new Exception("Aula não encontrada");
    }

    // Obter alunos atuais para o log
    $stmtGetAlunos = $conexao->prepare("SELECT aluno_id FROM evento_aluno WHERE evento_id = :evento_id");
    $stmtGetAlunos->bindValue(":evento_id", $evento_id, PDO::PARAM_INT);
    $stmtGetAlunos->execute();
    $alunos_atuais = $stmtGetAlunos->fetchAll(PDO::FETCH_COLUMN);

    // Dados anteriores para o log
    $dados_anteriores = [
        'evento_id' => $evento_id,
        'alunos' => $alunos_atuais
    ];

    // Remove os alunos antigos
    $stmtExcluir = $conexao->prepare("DELETE FROM evento_aluno WHERE evento_id = :evento_id");
    $stmtExcluir->bindValue(":evento_id", $evento_id, PDO::PARAM_INT);
    $stmtExcluir->execute();

    // Prepara array de IDs para o log
    $aluno_ids = [];

    // Insere os novos
    foreach ($alunos as $aluno) {
        $aluno_id = isset($aluno['id']) ? $aluno['id'] : $aluno;
        $aluno_ids[] = $aluno_id;
        
        $stmtInserir = $conexao->prepare("INSERT INTO evento_aluno (evento_id, aluno_id) VALUES (:evento_id, :aluno_id)");
        $stmtInserir->bindValue(":evento_id", $evento_id, PDO::PARAM_INT);
        $stmtInserir->bindValue(":aluno_id", $aluno_id, PDO::PARAM_INT);
        $stmtInserir->execute();
    }

    // Registrar a ação no log
    Log::registrarAulaAluno('EDITAR_ALUNOS_AULA', $evento_id, $aluno_ids, $dados_anteriores);

    $conexao->commit();

    echo json_encode(["mensagem" => "Alunos atualizados com sucesso"]);

} catch (Exception $e) {
    $conexao->rollBack();
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao editar alunos: " . $e->getMessage()]);
}
?>