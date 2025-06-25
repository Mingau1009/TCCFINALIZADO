<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

try {
    $pdo = Db::conexao();

    // Recebendo os dados via POST
    $nome_aluno = $_POST['nome_aluno'] ?? null;
    $dia_refeicao = $_POST['dia_refeicao'] ?? null;
    $tipo_refeicao = $_POST['tipo_refeicao'] ?? null;
    $horario_refeicao = $_POST['horario_refeicao'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    // Validação dos campos obrigatórios
    if (!$nome_aluno || !$dia_refeicao || !$tipo_refeicao || !$horario_refeicao) {
        // Log de tentativa com dados incompletos
        Log::registrar(
            "TENTATIVA_CADASTRO_DIETA_INCOMPLETA",
            "dieta",
            null,
            null,
            [
                "nome_aluno" => $nome_aluno,
                "dia_refeicao" => $dia_refeicao,
                "tipo_refeicao" => $tipo_refeicao,
                "horario_refeicao" => $horario_refeicao
            ]
        );
        
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Campos obrigatórios não preenchidos!"]);
        exit;
    }

    $sql = "INSERT INTO dieta (nome_aluno, dia_refeicao, tipo_refeicao, horario_refeicao, descricao) 
            VALUES (:nome_aluno, :dia_refeicao, :tipo_refeicao, :horario_refeicao, :descricao)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome_aluno' => $nome_aluno,
        ':dia_refeicao' => $dia_refeicao,
        ':tipo_refeicao' => $tipo_refeicao,
        ':horario_refeicao' => $horario_refeicao,
        ':descricao' => $descricao,
    ]);

    $dieta_id = $pdo->lastInsertId();

    // Log de cadastro bem-sucedido
    $logResult = Log::registrar(
        "CADASTRO_DIETA",
        "dieta",
        $dieta_id,
        null,
        [
            "nome_aluno" => $nome_aluno,
            "dia_refeicao" => $dia_refeicao,
            "tipo_refeicao" => $tipo_refeicao,
            "horario_refeicao" => $horario_refeicao,
            "descricao" => $descricao
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de cadastro de dieta");
    }

    http_response_code(201);
    echo json_encode(["status" => "success", "message" => "Dieta cadastrada com sucesso!", "id" => $dieta_id]);

} catch (Exception $e) {
    // Log de erro no cadastro
    $logResult = Log::registrar(
        "ERRO_CADASTRO_DIETA",
        "dieta",
        null,
        null,
        [
            "erro" => $e->getMessage(),
            "dados_tentativa" => [
                "nome_aluno" => $nome_aluno,
                "dia_refeicao" => $dia_refeicao,
                "tipo_refeicao" => $tipo_refeicao,
                "horario_refeicao" => $horario_refeicao,
                "descricao" => $descricao
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro no cadastro de dieta: " . $e->getMessage());
    }

    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao cadastrar dieta: " . $e->getMessage()]);
}