<?php

include_once './conexao.php';

$user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_NUMBER_INT);
$client_id = filter_input(INPUT_GET, 'client_id', FILTER_SANITIZE_NUMBER_INT);

if (!empty($user_id) && empty($client_id)) {
    $query = "SELECT evt.*, f.nome AS name, f.telefone AS phone,
                     a.nome AS name_cli, a.telefone AS phone_cli
              FROM events AS evt
              INNER JOIN funcionario AS f ON f.id = evt.user_id
              INNER JOIN aluno AS a ON a.id = evt.client_id
              WHERE evt.user_id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

} elseif (empty($user_id) && !empty($client_id)) {
    $query = "SELECT evt.*, f.nome AS name, f.telefone AS phone,
                     a.nome AS name_cli, a.telefone AS phone_cli
              FROM events AS evt
              INNER JOIN funcionario AS f ON f.id = evt.user_id
              INNER JOIN aluno AS a ON a.id = evt.client_id
              WHERE evt.client_id = :client_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);

} elseif (!empty($user_id) && !empty($client_id)) {
    $query = "SELECT evt.*, f.nome AS name, f.telefone AS phone,
                     a.nome AS name_cli, a.telefone AS phone_cli
              FROM events AS evt
              INNER JOIN funcionario AS f ON f.id = evt.user_id
              INNER JOIN aluno AS a ON a.id = evt.client_id
              WHERE evt.user_id = :user_id AND evt.client_id = :client_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':client_id', $client_id, PDO::PARAM_INT);

} else {
    $query = "SELECT evt.*, f.nome AS name, f.telefone AS phone,
                     a.nome AS name_cli, a.telefone AS phone_cli
              FROM events AS evt
              INNER JOIN funcionario AS f ON f.id = evt.user_id
              INNER JOIN aluno AS a ON a.id = evt.client_id";
    $stmt = $conn->prepare($query);
}

$stmt->execute();

$eventos = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'user_id' => $user_id,
        'name' => $name,
        'phone' => $phone,
        'client_id' => $client_id,
        'client_name' => $name_cli,
        'client_phone' => $phone_cli
    ];
}

echo json_encode($eventos);
?>
