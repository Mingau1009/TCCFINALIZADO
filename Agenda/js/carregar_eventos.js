// Manter uma referência global ao calendário
var calendar;

// Receber o SELETOR da janela modal cadastrar
const cadastrarModal = new bootstrap.Modal(document.getElementById("cadastrarModal"));

// Receber o SELETOR da janela modal visualizar
const visualizarModal = new bootstrap.Modal(document.getElementById("visualizarModal"));

// Receber o SELETOR "msgViewEvento"
const msgViewEvento = document.getElementById('msgViewEvento');

// Carregar os eventos
function carregarEventos() {

    // Receber o SELETOR calendar do atributo id
    var calendarEl = document.getElementById('calendar');

    // Receber o id do usuário do campo Select
    var user_id = document.getElementById('user_id').value;

    // Receber atributo do campo Select
    // Agora o client_id vem diretamente do valor do SELECT
    const client_id = document.getElementById('client_id').value;


    // Instanciar FullCalendar.Calendar e atribuir a variável calendar
    calendar = new FullCalendar.Calendar(calendarEl, {

        // Incluir o bootstrap 5
        themeSystem: 'bootstrap5',

        // Criar o cabeçalho do calendário
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

        // Definir o idioma usado no calendário
        locale: 'pt-br',

        // Definir a data inicial
        //initialDate: '2023-01-12',
        //initialDate: '2023-10-12',

        // Permitir clicar nos nomes dos dias da semana 
        navLinks: true,

        // Permitir clicar e arrastar o mouse sobre um ou vários dias no calendário
        selectable: true,

        // Indicar visualmente a área que será selecionada antes que o usuário solte o botão do mouse para confirmar a seleção
        selectMirror: true,

        // Permitir arrastar e redimensionar os eventos diretamente no calendário.
        editable: true,

        // Número máximo de eventos em um determinado dia, se for true, o número de eventos será limitado à altura da célula do dia
        dayMaxEvents: true,

        // Chamar o arquivo PHP para recuperar os eventos
        events: 'listar_evento.php?user_id=' + user_id + '&client_id=' + client_id,

        // Identificar o clique do usuário sobre o evento
        eventClick: function (info) {

            // Apresentar os detalhes do evento
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";
            document.getElementById("visualizar_id").innerText = info.event.id;
            document.getElementById("visualizar_name").innerText = info.event.extendedProps.name;
            document.getElementById("visualizar_phone").innerText = info.event.extendedProps.phone;
            document.getElementById("visualizar_client_name").innerText = info.event.extendedProps.client_name;
            document.getElementById("visualizar_client_phone").innerText = info.event.extendedProps.client_phone;
            document.getElementById("visualizar_user_id").innerText = info.event.extendedProps.user_id;
            document.getElementById("visualizar_client_id").innerText = info.event.extendedProps.client_id;
            

        // Restante do seu código para preencher modal
        document.getElementById("visualizar_title").innerText = info.event.title;
        document.getElementById("visualizar_start").innerText = info.event.start.toLocaleString();


            // Enviar os dados do evento para o formulário editar
            document.getElementById("edit_id").value = info.event.id;
            document.getElementById("edit_title").value = info.event.title;;
            document.getElementById("edit_start").value = converterData(info.event.start);
            document.getElementById("edit_color").value = info.event.backgroundColor;

            // Abrir a janela modal visualizar
            visualizarModal.show();
        },
        // Abrir a janela modal cadastrar quando clicar sobre o dia no calendário
        select: async function (info) {

            // Receber o SELETOR do campo usuário do formulário cadastrar
            var cadUserId = document.getElementById('cad_user_id');

            // Chamar o arquivo PHP responsável em recuperar os usuários do banco de dados
            const dados = await fetch('listar_usuarios.php?profissional=S');

            // Ler os dados
            const resposta = await dados.json();
            //console.log(resposta);

            // Acessar o IF quando encontrar usuário no banco de dados
            if (resposta['status']) {

                // Criar a opção selecione para o campo select usuários
                var opcoes = '<option value="">Selecione</option>';

                // Percorrer a lista de usuários
                for (var i = 0; i < resposta.dados.length; i++) {

                    // Criar a lista de opções para o campo select usuários
                    opcoes += `<option value="${resposta.dados[i]['id']}">${resposta.dados[i]['name']}</option>`;

                }

                // Enviar as opções para o campo select no HTML
                cadUserId.innerHTML = opcoes;

            } else {

                // Enviar a opção vazia para o campo select no HTML
                cadUserId.innerHTML = `<option value=''>${resposta['msg']}</option>`;

            }

            // Receber o SELETOR do campo cliente do formulário cadastrar
            var cadClientId = document.getElementById('cad_client_id');

            // Chamar o arquivo PHP responsável em recuperar os clientes do banco de dados
            const dadosClient = await fetch('listar_usuarios.php');

            // Ler os dados
            const respostaClient = await dadosClient.json();
            //console.log(respostaClient);

            // Acessar o IF quando encontrar cliente no banco de dados
            if (respostaClient['status']) {

                // Criar a opção selecione para o campo select clientes
                var opcoes = '<option value="">Selecione</option>';

                // Percorrer a lista de clientes
                for (var i = 0; i < respostaClient.dados.length; i++) {

                    // Criar a lista de opções para o campo select clientes
                    opcoes += `<option value="${respostaClient.dados[i]['id']}">${respostaClient.dados[i]['name']}</option>`;

                }

                // Enviar as opções para o campo select no HTML
                cadClientId.innerHTML = opcoes;

            } else {

                // Enviar a opção vazia para o campo select no HTML
                cadClientId.innerHTML = `<option value=''>${respostaClient['msg']}</option>`;

            }

            // Chamar a função para converter a data selecionada para ISO8601 e enviar para o formulário
            document.getElementById("cad_start").value = converterData(info.start);

            // Abrir a janela modal cadastrar
            cadastrarModal.show();
        }
    });

    // Retornar os dados do calendário
    return calendar;
}
document.addEventListener('DOMContentLoaded', function () {
    carregarEventosPorCliente();
});
