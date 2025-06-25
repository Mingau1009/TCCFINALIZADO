<?php
include_once './conexao.php';

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Recuperar dados do professor
$query_professor = "SELECT id, nome, telefone FROM funcionario WHERE id = :id LIMIT 1";
$result_professor = $conn->prepare($query_professor);
$result_professor->bindParam(':id', $dados['edit_user_id']);
$result_professor->execute();
$row_professor = $result_professor->fetch(PDO::FETCH_ASSOC);

// Recuperar dados do aluno
$query_aluno = "SELECT id, nome, telefone FROM aluno WHERE id = :id LIMIT 1";
$result_aluno = $conn->prepare($query_aluno);
$result_aluno->bindParam(':id', $dados['edit_client_id']);
$result_aluno->execute();
$row_aluno = $result_aluno->fetch(PDO::FETCH_ASSOC);

$query_edit_event = "UPDATE events SET title=:title, color=:color, start=:start, user_id=:user_id, client_id=:client_id WHERE id=:id";
$edit_event = $conn->prepare($query_edit_event);

$edit_event->bindParam(':title', $dados['edit_title']);
$edit_event->bindParam(':color', $dados['edit_color']);
$edit_event->bindParam(':start', $dados['edit_start']);
$edit_event->bindParam(':user_id', $dados['edit_user_id']);
$edit_event->bindParam(':client_id', $dados['edit_client_id']);
$edit_event->bindParam(':id', $dados['edit_id']);

if ($edit_event->execute()) {
    $retorna = [
        'status' => true, 
        'msg' => 'Evento editado com sucesso!', 
        'id' => $dados['edit_id'], 
        'title' => $dados['edit_title'], 
        'color' => $dados['edit_color'], 
        'start' => $dados['edit_start'], 
        'user_id' => $row_professor['id'], 
        'name' => $row_professor['nome'], 
        'phone' => $row_professor['telefone'],
        'client_id' => $row_aluno['id'], 
        'client_name' => $row_aluno['nome'],
        'client_phone' => $row_aluno['telefone']
    ];
} else {
    $retorna = ['status' => false, 'msg' => 'Erro: Evento não editado!'];
}

echo json_encode($retorna);