$(document).ready(function () {
    $(".botao-selecionar-matricula").on("click", function () {
        let {
            id,
            nome, 
            data_nascimento, 
            cpf,
            telefone, 
            endereco, 
            turno_disponivel,
            data_matricula,
            ativo
        } = $(this).data();

        $("#editar").modal("show");

        $("#formulario-editar input[name='id']").val(id);
        $("#formulario-editar input[name='nome']").val(nome);
        $("#formulario-editar input[name='data_nascimento']").val(data_nascimento);
        $("#formulario-editar input[name='cpf']").val(cpf);
        $("#formulario-editar input[name='telefone']").val(telefone);
        $("#formulario-editar input[name='endereco']").val(endereco);
        $("#formulario-editar input[name='turno_disponivel']").val(turno_disponivel);
        $("#formulario-editar input[name='data_matricula']").val(data_matricula);
        $("#formulario-editar select[name='ativo']").val(ativo);
    });
  });

