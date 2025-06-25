<?php
    $criaaulas = Db::conexao()->query("SELECT * FROM `criar_aulas`")->fetchAll(PDO::FETCH_OBJ);

    foreach ($criaaulas as $criaaula) {
?>
    <tr>
        <td><?php echo htmlspecialchars($criaaula->nome_aula); ?></td>
        <td><?php echo htmlspecialchars($criaaula->dia_semana); ?></td>
        <td><?php echo htmlspecialchars(substr($criaaula->horario_aula, 0, 5)); ?></td>
        <td><?php echo htmlspecialchars($criaaula->professor_aula); ?></td>
        <td><?php echo htmlspecialchars($criaaula->local_aula); ?></td>
        <td class="conteudo-esconder-pdf">
            <button 
                class="conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 editar-btn"
                data-id="<?php echo htmlspecialchars($criaaula->id); ?>"
                data-nome_aula="<?php echo htmlspecialchars($criaaula->nome_aula); ?>"
                data-dia_aula="<?php echo htmlspecialchars($criaaula->dia_aula); ?>"
                data-horario_aula="<?php echo htmlspecialchars($criaaula->horario_aula); ?>"
                data-professor_aula="<?php echo htmlspecialchars($criaaula->professor_aula); ?>"
                data-local_aula="<?php echo htmlspecialchars($criaaula->local_aula); ?>">
                EDITAR
            </button>
        </td>
    </tr>
<?php } ?>