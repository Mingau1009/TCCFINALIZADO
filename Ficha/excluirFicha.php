<?php
include("../Classe/Conexao.php");

$id = $_POST["id"];

$sql = "DELETE FROM ficha WHERE id = :id";
$executar = Db::conexao()->prepare($sql);
$executar->bindValue(":id", $id, PDO::PARAM_INT);
$executar->execute();

// também exclua os exercícios da ficha, se necessário
$sql2 = "DELETE FROM ficha_exercicio WHERE ficha_id = :id";
$executar2 = Db::conexao()->prepare($sql2);
$executar2->bindValue(":id", $id, PDO::PARAM_INT);
$executar2->execute();

echo "OK";
