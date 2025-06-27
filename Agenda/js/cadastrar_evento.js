document.addEventListener('DOMContentLoaded', function () {
    const formCadEvento = document.getElementById("formCadEvento");
    const msg = document.getElementById("msg");
    const msgCadEvento = document.getElementById("msgCadEvento");
    const btnCadEvento = document.getElementById("btnCadEvento");

    if (formCadEvento) {
        formCadEvento.addEventListener("submit", async (e) => {
            e.preventDefault();

            // PEGAR A DATA DO CAMPO START
            const startInput = document.querySelector('input[name="start"]');
            if (startInput) {
                const startDate = new Date(startInput.value);
                const hoje = new Date();
                hoje.setHours(0, 0, 0, 0); // Zera hora para comparar só datas

                if (startDate < hoje) {
                    msgCadEvento.innerHTML = `<div class="alert alert-danger" role="alert">A data de início não pode ser anterior a hoje.</div>`;
                    return;
                }
            }

            btnCadEvento.value = "Salvando...";

            const dadosForm = new FormData(formCadEvento);
            const dados = await fetch("cadastrar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            const resposta = await dados.json();

            if (!resposta['status']) {
                msgCadEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;
            } else {
                msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;
                msgCadEvento.innerHTML = "";
                formCadEvento.reset();

                const filtroProfissional = document.getElementById('user_id')?.value ?? "";
                const filtroAluno = document.getElementById('client_id')?.value ?? "";

                if ((filtroProfissional === "" || resposta['user_id'] == filtroProfissional) &&
                    (filtroAluno === "" || resposta['client_id'] == filtroAluno)) {

                    const novoEvento = {
                        id: resposta['id'],
                        title: resposta['title'],
                        color: resposta['color'],
                        start: resposta['start'],
                        user_id: resposta['user_id'],
                        name: resposta['name'],
                        phone: resposta['phone'],
                        client_id: resposta['client_id'],
                        client_name: resposta['client_name'],
                        client_phone: resposta['client_phone']
                    };

                    document.getElementById("visualizar_id").innerText = resposta['id'];
                    document.getElementById("visualizar_title").innerText = resposta['title'];
                    document.getElementById("visualizar_start").innerText = resposta['start'];
                    document.getElementById("visualizar_user_id").innerText = resposta['user_id'];
                    document.getElementById("visualizar_name").innerText = resposta['name'];
                    document.getElementById("visualizar_phone").innerText = resposta['phone'];
                    document.getElementById("visualizar_client_id").innerText = resposta['client_id'];
                    document.getElementById("visualizar_client_name").innerText = resposta['client_name'];
                    document.getElementById("visualizar_client_phone").innerText = resposta['client_phone'];

                    calendar.addEvent(novoEvento);
                    calendar.render();
                }

                removerMsg();
                cadastrarModal.hide();
            }

            btnCadEvento.value = "Cadastrar";
        });
    }
});
