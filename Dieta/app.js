$(document).ready(function() {
    
    $('#formulario-cadastrar-dieta').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'cadastrar.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function (resposta) {
                location.reload(); // Recarrega para atualizar a tabela
            },
            error: function () {
                alert('Erro ao cadastrar. Verifique os dados.');
            }
        });
    });

    // Quando clica no botão EDITAR, preenche o modal
    $('.botao-selecionar-dieta').on('click', function () {
    const modal = $('#editar');
    const form = $('#formulario-editar-dieta');

    form.find('input[name="id"]').val($(this).data('id'));
    form.find('select[name="nome_aluno"]').val($(this).data('nome_aluno'));
    form.find('select[name="dia_refeicao"]').val($(this).data('dia_refeicao'));
    form.find('select[name="tipo_refeicao"]').val($(this).data('tipo_refeicao'));
    form.find('input[name="horario_refeicao"]').val($(this).data('horario_refeicao'));
    form.find('textarea[name="descricao"]').val($(this).data('descricao'));

    modal.modal('show');
    });


    // Enviar dados do formulário de edição
    $('#formulario-editar-dieta').on('submit', function (e) {
    e.preventDefault();

        $.ajax({
            url: 'editar.php',
            type: 'POST',
            data: $(this).serialize(), // aqui o id será incluído automaticamente
            success: function (resposta) {
                location.reload();
            },
            error: function (xhr) {
                alert("Erro: " + xhr.responseText);
            }
        });
    });

});
$(document).on('click', '.botao-visualizar-descricao', function () {
    const descricao = $(this).data('descricao');
    $('#descricao-visualizar').text(descricao);
    $('#visualizarDescricaoModal').modal('show');
});


