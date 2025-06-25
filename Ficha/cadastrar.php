<?php

include("../Classe/Conexao.php");

$aluno_id = isset($_POST["aluno_id"]) ? $_POST["aluno_id"] : NULL;
$dia_treino = isset($_POST["dia_treino"]) ? $_POST["dia_treino"] : NULL;
$exercicios = isset($_POST["exercicios"]) ? $_POST["exercicios"] : [];

$conexao = Db::conexao();
$executarFicha = $conexao->prepare("INSERT INTO `ficha` (`aluno_id`, `dia_treino`) VALUES (:aluno_id, :dia_treino)");
$executarFicha->bindValue(":aluno_id", $aluno_id, PDO::PARAM_INT);
$executarFicha->bindValue(":dia_treino", $dia_treino, PDO::PARAM_STR); 
$executarFicha->execute();

$ficha_id = $conexao->lastInsertId();

foreach ($exercicios as $exercicio) {
    $executarFichaExercicio = Db::conexao()->prepare("INSERT INTO `ficha_exercicio` (`ficha_id`, `exercicio_id`, `num_series`, `num_repeticoes`, `tempo_descanso`) VALUES (:ficha_id, :exercicio_id, :num_series, :num_repeticoes, :tempo_descanso)");
    $executarFichaExercicio->bindValue(":ficha_id", $ficha_id, PDO::PARAM_INT);
    $executarFichaExercicio->bindValue(":exercicio_id", $exercicio["exercicio_id"], PDO::PARAM_INT);
    $executarFichaExercicio->bindValue(":num_series", $exercicio["num_series"], PDO::PARAM_INT); 
    $executarFichaExercicio->bindValue(":num_repeticoes", $exercicio["num_repeticoes"], PDO::PARAM_INT); 
    $executarFichaExercicio->bindValue(":tempo_descanso", $exercicio["tempo_descanso"], PDO::PARAM_INT); 
    $executarFichaExercicio->execute();
}