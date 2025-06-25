<?php

include("../Classe/Conexao.php");

$id = isset($_GET["id"]) ? $_GET["id"] : NULL;

$conexao = Db::conexao();
$executarFicha = $conexao->prepare("DELETE FROM `ficha_exercicio` WHERE `id` = :id");
$executarFicha->bindValue(":id", $id, PDO::PARAM_INT); 
$executarFicha->execute();
 
header("Location: ./");