$(document).on("click", ".editar-btn", function () {
    const { id, nome_aula, dia_aula, horario_aula, professor_aula, local_aula } = $(this).data();

    console.log({ id, nome_aula, dia_aula, horario_aula, professor_aula, local_aula });

    $("#formulario-editar input[name='id']").val(id);
    $("#formulario-editar input[name='nome_aula']").val(nome_aula);
    $("#formulario-editar select[name='dia_aula']").val(dia_aula);
    $("#formulario-editar input[name='horario_aula']").val(horario_aula);
    $("#formulario-editar select[name='professor_aula']").val(professor_aula);
    $("#formulario-editar select[name='local_aula']").val(local_aula);

    $("#editar").modal("show");
});

