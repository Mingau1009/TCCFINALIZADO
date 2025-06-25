function carregarEventosPorCliente() {
    const selectCliente = document.getElementById('client_id');

    if (selectCliente) {
        // Verifica se já há eventos adicionados para evitar múltiplos listeners
        if (!selectCliente.hasAttribute('data-listener-added')) {

            // Adiciona listener para carregar eventos ao mudar o cliente
            selectCliente.addEventListener('change', function () {
                calendar = carregarEventos();
                calendar.render();
            });

            // Marcar que o listener já foi adicionado
            selectCliente.setAttribute('data-listener-added', 'true');
        }
    }
}
