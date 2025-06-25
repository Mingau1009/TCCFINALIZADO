<?php
function base64UrlEncode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
    return base64_decode(strtr($data, '-_', '+/'));
}

function criarJWT($payload, $chave) {
    $header = ['alg' => 'HS256', 'typ' => 'JWT'];
    $header_encoded = base64UrlEncode(json_encode($header));
    $payload_encoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $chave, true);
    $signature_encoded = base64UrlEncode($signature);

    return "$header_encoded.$payload_encoded.$signature_encoded";
}

function verificarJWT($jwt, $chave) {
    $partes = explode('.', $jwt);
    if (count($partes) !== 3) return false;

    list($header_encoded, $payload_encoded, $signature_provided) = $partes;

    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $chave, true);
    $signature_encoded = base64UrlEncode($signature);

    return hash_equals($signature_encoded, $signature_provided) ? json_decode(base64UrlDecode($payload_encoded), true) : false;
}
?>