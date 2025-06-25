document.addEventListener('DOMContentLoaded', async function () {
    const clientSelect = document.getElementById('client_id');

    if (clientSelect) {
        try {
            const response = await fetch('listar_clientes.php');
            const data = await response.json();

            if (data.status) {
                clientSelect.innerHTML = "<option value=''>Selecione</option>";
                data.dados.forEach(function (cliente) {
                    clientSelect.innerHTML += `<option value="${cliente.id}">${cliente.nome}</option>`;
                });
            } else {
                clientSelect.innerHTML = "<option value=''>Nenhum cliente encontrado</option>";
            }
        } catch (erro) {
            console.error('Erro ao carregar clientes:', erro);
        }
    }
});
