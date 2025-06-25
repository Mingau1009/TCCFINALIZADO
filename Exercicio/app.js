$(document).on("click", ".botao-selecionar-exercicio", function () {
    const { id, nome, tipo_exercicio, grupo_muscular } = $(this).data();

    $("#editar").modal("show");

    $("#formulario-editar input[name='id']").val(id);
    $("#formulario-editar input[name='nome']").val(nome);
    $("#formulario-editar select[name='tipo_exercicio']").val(tipo_exercicio);
    $("#formulario-editar select[name='grupo_muscular']").val(grupo_muscular);
});
