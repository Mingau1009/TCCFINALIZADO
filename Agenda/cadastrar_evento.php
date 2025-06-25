<?php
include_once './conexao.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Recuperar dados do professor
$query_professor = "SELECT id, nome, telefone FROM funcionario WHERE id = :id LIMIT 1";
$result_professor = $conn->prepare($query_professor);
$result_professor->bindParam(':id', $dados['cad_user_id']);
$result_professor->execute();
$row_professor = $result_professor->fetch(PDO::FETCH_ASSOC);

// Recuperar dados do aluno
$query_aluno = "SELECT id, nome, telefone FROM aluno WHERE id = :id LIMIT 1";
$result_aluno = $conn->prepare($query_aluno);
$result_aluno->bindParam(':id', $dados['cad_client_id']);
$result_aluno->execute();
$row_aluno = $result_aluno->fetch(PDO::FETCH_ASSOC);

$query_cad_event = "INSERT INTO events (title, color, start, user_id, client_id) VALUES (:title, :color, :start, :user_id, :client_id)";
$cad_event = $conn->prepare($query_cad_event);

$cad_event->bindParam(':title', $dados['cad_title']);
$cad_event->bindParam(':color', $dados['cad_color']);
$cad_event->bindParam(':start', $dados['cad_start']);
$cad_event->bindParam(':user_id', $dados['cad_user_id']);
$cad_event->bindParam(':client_id', $dados['cad_client_id']);

if ($cad_event->execute()) {
    $retorna = [
        'status' => true, 
        'msg' => 'Evento cadastrado com sucesso!', 
        'id' => $conn->lastInsertId(), 
        'title' => $dados['cad_title'], 
        'color' => $dados['cad_color'], 
        'start' => $dados['cad_start'], 
        'user_id' => $row_professor['id'], 
        'name' => $row_professor['nome'], 
        'phone' => $row_professor['telefone'],
        'client_id' => $row_aluno['id'], 
        'client_name' => $row_aluno['nome'],
        'client_phone' => $row_aluno['telefone']
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não cadastrado!'];
}

echo json_encode($retorna);