<?php

include("../Classe/Conexao.php");

$id = isset($_POST["id"]) ? $_POST["id"] : NULL;
$dia_treino = isset($_POST["dia_treino"]) ? $_POST["dia_treino"] : NULL;
$exercicios = isset($_POST["exercicios"]) ? $_POST["exercicios"] : [];

$conexao = Db::conexao();
$executarFicha = $conexao->prepare("UPDATE `ficha` SET `dia_treino` = :dia_treino WHERE `id` = :id");
$executarFicha->bindValue(":id", $id, PDO::PARAM_INT);
$executarFicha->bindValue(":dia_treino", $dia_treino, PDO::PARAM_STR); 
$executarFicha->execute();

$ficha_id = $id;

$executarExcluirFicha = $conexao->prepare("DELETE FROM `ficha_exercicio` WHERE `ficha_id` = :ficha_id");
$executarExcluirFicha->bindValue(":ficha_id", $ficha_id, PDO::PARAM_INT);
$executarExcluirFicha->execute();

foreach ($exercicios as $exercicio) {
    $executarFichaExercicio = Db::conexao()->prepare("INSERT INTO `ficha_exercicio` (`ficha_id`, `exercicio_id`, `num_series`, `num_repeticoes`, `tempo_descanso`) VALUES (:ficha_id, :exercicio_id, :num_series, :num_repeticoes, :tempo_descanso)");
    $executarFichaExercicio->bindValue(":ficha_id", $ficha_id, PDO::PARAM_INT);
    $executarFichaExercicio->bindValue(":exercicio_id", $exercicio["exercicio_id"], PDO::PARAM_INT);
    $executarFichaExercicio->bindValue(":num_series", $exercicio["num_series"], PDO::PARAM_INT); 
    $executarFichaExercicio->bindValue(":num_repeticoes", $exercicio["num_repeticoes"], PDO::PARAM_INT); 
    $executarFichaExercicio->bindValue(":tempo_descanso", $exercicio["tempo_descanso"], PDO::PARAM_INT); 
    $executarFichaExercicio->execute();
}