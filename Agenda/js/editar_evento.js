document.addEventListener('DOMContentLoaded', function () {    
    const btnViewEditEvento = document.getElementById("btnViewEditEvento");
    const btnViewEvento = document.getElementById("btnViewEvento");
    const formEditEvento = document.getElementById("formEditEvento");
    const msgEditEvento = document.getElementById("msgEditEvento");
    const btnEditEvento = document.getElementById("btnEditEvento");

    if (btnViewEditEvento) {
        btnViewEditEvento.addEventListener("click", async () => {
            document.getElementById("visualizarEvento").style.display = "none";
            document.getElementById("visualizarModalLabel").style.display = "none";
            document.getElementById("editarEvento").style.display = "block";
            document.getElementById("editarModalLabel").style.display = "block";

            // Carregar professores
            const userId = document.getElementById('visualizar_user_id').innerText;
            const editUserId = document.getElementById('edit_user_id');
            const dadosProf = await fetch('listar_usuarios.php?profissional=S');
            const respostaProf = await dadosProf.json();

            if (respostaProf['status']) {
                let opcoes = '<option value="">Selecione um professor</option>';
                respostaProf.dados.forEach(prof => {
                    opcoes += `<option value="${prof.id}" ${userId == prof.id ? 'selected' : ""}>${prof.name}</option>`;
                });
                editUserId.innerHTML = opcoes;
            } else {
                editUserId.innerHTML = `<option value=''>${respostaProf['msg']}</option>`;
            }

            // Carregar alunos
            const clientId = document.getElementById('visualizar_client_id').innerText;
            const editClientId = document.getElementById('edit_client_id');
            const dadosAluno = await fetch('listar_usuarios.php');
            const respostaAluno = await dadosAluno.json();

            if (respostaAluno['status']) {
                let opcoes = '<option value="">Selecione um aluno</option>';
                respostaAluno.dados.forEach(aluno => {
                    opcoes += `<option value="${aluno.id}" ${clientId == aluno.id ? 'selected' : ""}>${aluno.name}</option>`;
                });
                editClientId.innerHTML = opcoes;
            } else {
                editClientId.innerHTML = `<option value=''>${respostaAluno['msg']}</option>`;
            }
        });
    }

    if (btnViewEvento) {
        btnViewEvento.addEventListener("click", () => {
            document.getElementById("visualizarEvento").style.display = "block";
            document.getElementById("visualizarModalLabel").style.display = "block";
            document.getElementById("editarEvento").style.display = "none";
            document.getElementById("editarModalLabel").style.display = "none";
        });
    }

    if (formEditEvento) {
        formEditEvento.addEventListener("submit", async (e) => {
            e.preventDefault();
            btnEditEvento.value = "Salvando...";

            const dadosForm = new FormData(formEditEvento);
            const dados = await fetch("editar_evento.php", {
                method: "POST",
                body: dadosForm
            });

            const resposta = await dados.json();

            if (!resposta['status']) {
                msgEditEvento.innerHTML = `<div class="alert alert-danger" role="alert">${resposta['msg']}</div>`;
            } else {
                msg.innerHTML = `<div class="alert alert-success" role="alert">${resposta['msg']}</div>`;
                msgEditEvento.innerHTML = "";
                formEditEvento.reset();

                const eventoExiste = calendar.getEventById(resposta['id']);
                const user_id = document.getElementById('user_id').value;
                const inputClienteId = document.getElementById('client_id');
                const client_id = inputClienteId.getAttribute('data-target-pesq-client-id');

                if ((user_id == "" || resposta['user_id'] == user_id) && (client_id == "" || resposta['client_id'] == client_id)) {
                    if (eventoExiste) {
                        eventoExiste.setProp('title', resposta['title']);
                        eventoExiste.setProp('color', resposta['color']);
                        eventoExiste.setExtendedProp('user_id', resposta['user_id']);
                        eventoExiste.setExtendedProp('name', resposta['name']);
                        eventoExiste.setExtendedProp('phone', resposta['phone']);
                        eventoExiste.setExtendedProp('client_id', resposta['client_id']);
                        eventoExiste.setExtendedProp('client_name', resposta['client_name']);
                        eventoExiste.setExtendedProp('client_phone', resposta['client_phone']);
                        eventoExiste.setStart(resposta['start']);

                    }
                } else {
                    if (eventoExiste) {
                        eventoExiste.remove();
                    }
                }

                removerMsg();
                visualizarModal.hide();
            }
            btnEditEvento.value = "Salvar";
        });
    }
});