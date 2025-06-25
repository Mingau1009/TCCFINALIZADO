<?php
require 'jwt.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Last-Activity');

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

if (!$token) {
    http_response_code(401);
    echo json_encode(['valido' => false]);
    exit;
}

$chave = 'chave-secreta';
$payload = verificarJWT($token, $chave);

if (!$payload) {
    http_response_code(401);
    echo json_encode(['valido' => false]);
    exit;
}

// Verificar se o tempo de inatividade foi excedido
$lastActivityHeader = $_SERVER['HTTP_X_LAST_ACTIVITY'] ?? null;
if ($lastActivityHeader && isset($payload['tempo_maximo_inatividade'])) {
    $lastActivity = intval($lastActivityHeader) / 1000; // Converter de ms para segundos
    $currentTime = time();
    $inactiveTime = $currentTime - $lastActivity;

    if ($inactiveTime > $payload['tempo_maximo_inatividade']) {
        http_response_code(401);
        echo json_encode(['valido' => false]);
        exit;
    }
}

echo json_encode(['valido' => true]);
?>