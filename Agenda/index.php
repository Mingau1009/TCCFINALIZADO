<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
    <link href="css/custom.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../Sidebar/style.css">
    <script src="../Login/inactivity.js"></script>
    <title>Agendamento</title>
</head>
<body>

<?php include("../Classe/Conexao.php") ?>

<?php include("../Sidebar/index.php"); ?>
<?php
// Buscar professores (usuários) ativos
$professores = Db::conexao()->query("SELECT id, nome FROM funcionario WHERE ativo = 1 ORDER BY nome ASC")->fetchAll(PDO::FETCH_OBJ);

// Buscar alunos ativos
$alunos = Db::conexao()->query("SELECT id, nome FROM aluno WHERE ativo = 1 ORDER BY nome ASC")->fetchAll(PDO::FETCH_OBJ);
?>

    <div class="container">

        <div class="card mb-4 border-light shadow">
            <div class="card-body">
                <h2 class="mt-0 me-3 ms-2 pb-2 border-bottom">Agenda</h2>

<section class="p-3" style="margin-left:85px;"></section>

                <span id="msg"></span>

                <form class="ms-2 me-2 row g-3">

                    <div class="col-md-5 col-sm-12">
                        <label class="form-label" for="user_id">Pesquisar eventos do profissional</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">Selecione</option>
                        </select>
                    </div>

                    <div class="col-md-5 col-sm-12">
                        <label class="form-label" for="client_id">Pesquisar eventos do cliente</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">Selecione</option>
                        </select>
                    </div>


                </form>

            </div>
        </div>

        <div class="card p-4 border-light shadow">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="visualizarModal" tabindex="-1" aria-labelledby="visualizarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <h1 class="modal-title fs-5" id="visualizarModalLabel">Visualizar o Evento</h1>

                    <h1 class="modal-title fs-5" id="editarModalLabel" style="display: none;">Editar o Evento</h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgViewEvento"></span>

                    <div id="visualizarEvento">

                        <dl class="row">
                            
                            <dt class="col-sm-3">ID: </dt>
                            <dd class="col-sm-9" id="visualizar_id"></dd>

                            <dt class="col-sm-3">Título: </dt>
                            <dd class="col-sm-9" id="visualizar_title"></dd>

                            <dt class="col-sm-3">Início: </dt>
                            <dd class="col-sm-9" id="visualizar_start"></dd>

                            <dt class="col-sm-3">ID do Profissional: </dt>
                            <dd class="col-sm-9" id="visualizar_user_id"></dd>

                            <dt class="col-sm-3">Nome do Profissional: </dt>
                            <dd class="col-sm-9" id="visualizar_name"></dd>

                            <dt class="col-sm-3">Telefone do Profissional: </dt>
                            <dd class="col-sm-9" id="visualizar_phone"></dd>

                            <dt class="col-sm-3">ID do Cliente: </dt>
                            <dd class="col-sm-9" id="visualizar_client_id"></dd>

                            <dt class="col-sm-3">Nome do Cliente: </dt>
                            <dd class="col-sm-9" id="visualizar_client_name"></dd>

                            <dt class="col-sm-3">Telefone do Cliente: </dt>
                            <dd class="col-sm-9" id="visualizar_client_phone"></dd>

                        </dl>

                        <button type="button" class="btn btn-primary" id="btnApagarEvento">Apagar</button>

                        <button type="button" class="btn btn-warning" id="btnViewEditEvento">Editar</button>

                        <button type="button" class="btn btn-danger" onclick="gerarPDF()">Gerar PDF</button>
              
                    </div>

                    <div id="editarEvento" style="display: none;">

                        <span id="msgEditEvento"></span>

                        <form method="POST" id="formEditEvento">

                            <input type="hidden" name="edit_id" id="edit_id">

                            <div class="row mb-3">
                            <label for="edit_title" class="col-sm-2 col-form-label">Título</label>
                                <div class="col-sm-10">
                                    <select name="edit_title" class="form-control" id="edit_title">
                                        <option value="" selected disabled>Selecione um título</option>
                                        <option value="Encontro com Nutricionista">Encontro com Nutricionista</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="edit_start" class="col-sm-2 col-form-label">Início</label>
                                <div class="col-sm-10">
                                    <input type="datetime-local" name="edit_start" class="form-control" id="edit_start">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_color" class="col-sm-2 col-form-label">Cor</label>
                                <div class="col-sm-10">
                                    <select name="edit_color" class="form-control" id="edit_color">
                                        <option value="">Selecione</option>
                                        <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                        <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                        <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                        <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                        <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                        <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                        <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                        <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                        <option style="color:#228B22;" value="#228B22">Verde</option>
                                        <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_user_id" class="col-sm-2 col-form-label">Professor</label>
                                <div class="col-sm-10">
                                    <select name="edit_user_id" class="form-control" id="edit_user_id" required>
                                        <option value="">Selecione um professor</option>
                                        <?php foreach($professores as $professor): ?>
                                            <option value="<?php echo $professor->id; ?>"><?php echo htmlspecialchars($professor->nome); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="edit_client_id" class="col-sm-2 col-form-label">Aluno</label>
                                <div class="col-sm-10">
                                    <select name="edit_client_id" class="form-control" id="edit_client_id" required>
                                        <option value="">Selecione um aluno</option>
                                        <?php foreach($alunos as $aluno): ?>
                                            <option value="<?php echo $aluno->id; ?>"><?php echo htmlspecialchars($aluno->nome); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <button type="button" name="btnViewEvento" class="btn btn-danger" id="btnViewEvento">Cancelar</button>

                            <button type="submit" name="btnEditEvento" class="btn btn-success" id="btnEditEvento">Salvar</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cadastrar -->
    <div class="modal fade" id="cadastrarModal" tabindex="-1" aria-labelledby="cadastrarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="cadastrarModalLabel">Cadastrar o Evento</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <span id="msgCadEvento"></span>

                    <form method="POST" id="formCadEvento">

                        <div class="row mb-3">
                            <label for="cad_title" class="col-sm-2 col-form-label">Título</label>
                            <div class="col-sm-10">
                                <select name="cad_title" class="form-control" id="cad_title">
                                    <option value="" selected disabled>Selecione um título</option>
                                    <option value="Encontro com Nutricionista">Encontro com Nutricionista</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_start" class="col-sm-2 col-form-label">Início</label>
                            <div class="col-sm-10">
                                <input type="datetime-local" name="cad_start" class="form-control" id="cad_start">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="cad_color" class="col-sm-2 col-form-label">Cor</label>
                            <div class="col-sm-10">
                                <select name="cad_color" class="form-control" id="cad_color">
                                    <option value="">Selecione</option>
                                    <option style="color:#FFD700;" value="#FFD700">Amarelo</option>
                                    <option style="color:#0071c5;" value="#0071c5">Azul Turquesa</option>
                                    <option style="color:#FF4500;" value="#FF4500">Laranja</option>
                                    <option style="color:#8B4513;" value="#8B4513">Marrom</option>
                                    <option style="color:#1C1C1C;" value="#1C1C1C">Preto</option>
                                    <option style="color:#436EEE;" value="#436EEE">Royal Blue</option>
                                    <option style="color:#A020F0;" value="#A020F0">Roxo</option>
                                    <option style="color:#40E0D0;" value="#40E0D0">Turquesa</option>
                                    <option style="color:#228B22;" value="#228B22">Verde</option>
                                    <option style="color:#8B0000;" value="#8B0000">Vermelho</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                        <label for="cad_user_id" class="col-sm-2 col-form-label">Profissional</label>
                        <div class="col-sm-10">
                            <select name="cad_user_id" class="form-control" id="cad_user_id" required>
                                <option value="">Selecione um professor</option>
                                <?php foreach($professores as $professor): ?>
                                    <option value="<?php echo $professor->id; ?>"><?php echo htmlspecialchars($professor->nome); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="cad_client_id" class="col-sm-2 col-form-label">Aluno</label>
                        <div class="col-sm-10">
                            <select name="cad_client_id" class="form-control" id="cad_client_id" required>
                                <option value="">Selecione um aluno</option>
                                <?php foreach($alunos as $aluno): ?>
                                    <option value="<?php echo $aluno->id; ?>"><?php echo htmlspecialchars($aluno->nome); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                        <button type="submit" name="btnCadEvento" class="btn btn-success" id="btnCadEvento">Cadastrar</button>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src='js/index.global.min.js'></script>
    <script src="js/bootstrap5/index.global.min.js"></script>
    <script src='js/core/locales-all.global.min.js'></script>
    <script src='js/custom.js'></script>
    <script src='js/converter_data.js'></script>
    <script src='js/carregar_eventos_profissional.js'></script>
    <script src='js/carregar_eventos_cliente.js'></script>
    <script src="js/listar_clientes.js"></script>
    <script src='js/carregar_eventos.js'></script>
    <script src='js/listar_usuario.js'></script>
    <script src='js/pesquisar_cliente.js'></script>
    <script src='js/cadastrar_evento.js'></script>
    <script src='js/editar_evento.js'></script>
    <script src='js/apagar_evento.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
function gerarPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const data = {
        "Título": document.getElementById("visualizar_title").innerText,
        "Início": document.getElementById("visualizar_start").innerText,
        "Nome do Profissional": document.getElementById("visualizar_name").innerText,
        "Telefone do Profissional": document.getElementById("visualizar_phone").innerText,
        "Nome do Cliente": document.getElementById("visualizar_client_name").innerText,
        "Telefone do Cliente": document.getElementById("visualizar_client_phone").innerText
    };

    let y = 10; 
    doc.setFont("helvetica", "bold");
    doc.setFontSize(16);
    doc.text("Detalhes do Evento", 10, y);
    y += 10;

    doc.setFont("helvetica", "normal");
    doc.setFontSize(12);

    for (let key in data) {
        doc.text(`${key}: ${data[key]}`, 10, y);
        y += 8; 
    }

    // Add footer with date and page number
    const date = new Date();
    const formattedDate = date.toLocaleString('pt-BR', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric', 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
    });

    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(10);
        doc.text(formattedDate, 10, doc.internal.pageSize.height - 10);
        doc.text(`Página ${i} de ${pageCount}`, doc.internal.pageSize.width - 40, doc.internal.pageSize.height - 10);
    }

    doc.save("evento.pdf");
}
</script>



</body>

</html>