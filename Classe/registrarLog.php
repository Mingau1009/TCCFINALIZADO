<?php
include("Conexao.php");
include("Log.php");

header('Content-Type: application/json');

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSON inválido');
    }
    
    $result = Log::registrar(
        $data['acao'],
        $data['tabela_afetada'] ?? null,
        $data['registro_id'] ?? null,
        $data['dados_anteriores'] ?? null,
        $data['dados_novos'] ?? null
    );
    
    echo json_encode(['success' => $result]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}