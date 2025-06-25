<?php
session_start();
require 'jwt.php';

// Carrega credenciais de forma segura
$config = require 'config.php';
$usuario_correto = $config['usuario_autorizado'];
$senha_correta = $config['senha_autorizada'];

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

function validarEntrada($dados) {
    $erros = [];

    if (!isset($dados['usuario']) || !filter_var($dados['usuario'], FILTER_VALIDATE_EMAIL)) {
        $erros['usuario'] = 'Usuário é obrigatório e deve ser um e-mail válido.';
    }

    if (!isset($dados['senha']) || strlen(trim($dados['senha'])) < 2) {
        $erros['senha'] = 'Senha é obrigatória e deve ter pelo menos 2 caracteres.';
    }

    return $erros;
}

$input = json_decode(file_get_contents("php://input"), true);
$erros = validarEntrada($input);

if (!empty($erros)) {
    http_response_code(400);
    echo json_encode(['erros' => $erros]);
    exit;
}

$usuario = $input['usuario'];
$senha = $input['senha'];

// Inicializa contadores de tentativas e tempo de bloqueio
if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}
if (!isset($_SESSION['bloqueado_ate'])) {
    $_SESSION['bloqueado_ate'] = 0;
}

// Verifica se o usuário está bloqueado
if (time() < $_SESSION['bloqueado_ate']) {
    $tempo_restante = $_SESSION['bloqueado_ate'] - time();
    http_response_code(403);
    echo json_encode([
        'erro' => 'Acesso bloqueado temporariamente. Tente novamente em ' . $tempo_restante . ' segundos.',
        'resetar_campos' => true,
        'bloqueado_ate' => $_SESSION['bloqueado_ate']
    ]);
    exit;
}

// Verifica login e senha
if ($usuario === $usuario_correto && $senha === $senha_correta) {
    $_SESSION['tentativas'] = 0;
    $chave = 'chave-secreta';
    $payload = [
        'usuario' => $usuario,
        'inicio_inatividade' => null, // Será definido quando o mouse ficar inativo
        'tempo_maximo_inatividade' => 10 // 2 minutos de inatividade
    ];

    $token = criarJWT($payload, $chave);
    echo json_encode(['token' => $token]);
} else {
    $_SESSION['tentativas']++;

    if ($_SESSION['tentativas'] >= 3) {
        $_SESSION['bloqueado_ate'] = time() + 60;
        $_SESSION['tentativas'] = 0;
        http_response_code(403);
        echo json_encode([
            'erro' => 'Número máximo de tentativas excedido. Tente novamente em 60 segundos.',
            'resetar_campos' => true,
            'bloqueado_ate' => $_SESSION['bloqueado_ate']
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'erro' => 'Usuário ou senha inválidos',
            'tentativas_restantes' => 3 - $_SESSION['tentativas']
        ]);
    }
}
?>