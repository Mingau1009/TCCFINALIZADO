<?php
include("../Classe/Conexao.php");
include("../Classe/Log.php");

try {
    $pdo = Db::conexao();

    // Coleta os dados do formulário
    $id = $_POST['id'] ?? null;
    $nome_aluno = $_POST['nome_aluno'] ?? null;
    $dia_refeicao = $_POST['dia_refeicao'] ?? null;
    $tipo_refeicao = $_POST['tipo_refeicao'] ?? null;
    $horario_refeicao = $_POST['horario_refeicao'] ?? null;
    $descricao = $_POST['descricao'] ?? null;

    // Verifica se o ID foi enviado
    if (!$id) {
        // Log de tentativa sem ID
        Log::registrar(
            "TENTATIVA_EDICAO_DIETA_SEM_ID",
            "dieta",
            null,
            null,
            ["id" => $id]
        );
        
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "ID da dieta não informado."]);
        exit;
    }

    // Validação dos campos obrigatórios
    if (!$nome_aluno || !$dia_refeicao || !$tipo_refeicao || !$horario_refeicao) {
        // Log de tentativa com dados incompletos
        Log::registrar(
            "TENTATIVA_EDICAO_DIETA_INCOMPLETA",
            "dieta",
            $id,
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

    // Obter dados atuais para log
    $consultaAtual = $pdo->prepare("SELECT * FROM dieta WHERE id = :id");
    $consultaAtual->bindValue(":id", $id, PDO::PARAM_INT);
    $consultaAtual->execute();
    $dadosAntigos = $consultaAtual->fetch(PDO::FETCH_ASSOC);

    // Prepara e executa o UPDATE
    $sql = "UPDATE dieta SET 
                nome_aluno = :nome_aluno,
                dia_refeicao = :dia_refeicao,
                tipo_refeicao = :tipo_refeicao,
                horario_refeicao = :horario_refeicao,
                descricao = :descricao
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':nome_aluno' => $nome_aluno,
        ':dia_refeicao' => $dia_refeicao,
        ':tipo_refeicao' => $tipo_refeicao,
        ':horario_refeicao' => $horario_refeicao,
        ':descricao' => $descricao
    ]);

    // Dados novos para log
    $dadosNovos = [
        "nome_aluno" => $nome_aluno,
        "dia_refeicao" => $dia_refeicao,
        "tipo_refeicao" => $tipo_refeicao,
        "horario_refeicao" => $horario_refeicao,
        "descricao" => $descricao
    ];

    // Log de edição bem-sucedida
    $logResult = Log::registrar(
        "EDICAO_DIETA",
        "dieta",
        $id,
        $dadosAntigos,
        $dadosNovos
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de edição de dieta");
    }

    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Dieta atualizada com sucesso!"]);

} catch (Exception $e) {
    // Log de erro na edição
    $logResult = Log::registrar(
        "ERRO_EDICAO_DIETA",
        "dieta",
        $id ?? null,
        null,
        [
            "erro" => $e->getMessage(),
            "dados_tentativa" => [
                "id" => $id,
                "nome_aluno" => $nome_aluno,
                "dia_refeicao" => $dia_refeicao,
                "tipo_refeicao" => $tipo_refeicao,
                "horario_refeicao" => $horario_refeicao,
                "descricao" => $descricao
            ]
        ]
    );

    if (!$logResult) {
        error_log("Falha ao registrar log de erro na edição de dieta: " . $e->getMessage());
    }

    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Erro ao editar dieta: " . $e->getMessage()]);
}