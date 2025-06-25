<?php

include("../Classe/Conexao.php");

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;

$conexao = Db::conexao();
$executarFicha = $conexao->prepare("SELECT `ficha_exercicio`.*, `exercicio`.`nome` AS exercicio_nome FROM `ficha_exercicio` INNER JOIN `exercicio` ON `exercicio`.`id` = `ficha_exercicio`.`exercicio_id` WHERE `ficha_exercicio`.`ficha_id` = :ficha_id ORDER BY `exercicio`.`nome` ASC");
$executarFicha->bindValue(":ficha_id", $id, PDO::PARAM_INT);
$executarFicha->execute();
 
$exercicios = $executarFicha->fetchAll(PDO::FETCH_OBJ);

echo json_encode(["exercicios" => $exercicios]);