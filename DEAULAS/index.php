<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/af6fbadd15.js" crossorigin="anonymous"></script>
    <title>Área de cadastro de aula</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../Sidebar/style.css">
    <script src="../Login/inactivity.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

<?php include("../Classe/Conexao.php") ?>
<?php include("../Sidebar/index.php"); ?>

<?php
$queryProfessores = Db::conexao()->query("SELECT id, nome FROM funcionario ORDER BY nome");
$professores = Db::conexao()->query("SELECT nome FROM funcionario WHERE ativo = 1")->fetchAll(PDO::FETCH_ASSOC);

$pesquisa = isset($_GET['pesquisa']) ? $_GET['pesquisa'] : '';
$queryAulas = Db::conexao()->query("SELECT * FROM criar_aula WHERE nome_aula LIKE '%$pesquisa%' ORDER BY nome_aula");
$aulas = $queryAulas->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="p-3" style="margin-left:85px;">
    <div class="text-end mb-2 conteudo-esconder-pdf">
        <button class="btn btn-success newUser " data-bs-toggle="modal" data-bs-target="#userForm">
            CADASTRAR AULA <i class="bi bi-people"></i>
        </button>
    </div>
    <form method="get" class="mb-2 conteudo-esconder-pdf">
        <div class="row">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="hidden" name="ordenar" value="<?php echo $ordenar; ?>">
                    <input name="pesquisa" value="<?php echo $pesquisa; ?>" type="text" class="form-control" placeholder="Buscar por nome...">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
            </div>
        </div>
    </form>
    <div class="col-12 text-end conteudo-esconder-pdf">
        <div class="d-inline">
            <button class="btn btn-danger botao-gerar-pdf">
                <i class="bi bi-file-earmark-pdf"></i> GERAR PDF
            </button>
        </div>
    </div>
</section>

<style>
    .short-input {
        width: 800px !important; 
        max-width: 100%; 
    }

    .input-group {
        max-width: 545px;  
    }

    .search-input {
        max-width: 545px;  
    }

    body {
        margin-left: 120px;
    }
</style>

<section>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-hover mt-3 text-center table-bordered">
                    <thead>
                        <tr>
                            <th>NOME DA AULA</th>
                            <th>DIA DA AULA</th>
                            <th>HORARIO DA AULA</th>
                            <th>PROFESSOR</th>
                            <th>LOCAL DE AULA</th>
                            <th>AJUSTES</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (!empty($aulas)) {
                            foreach ($aulas as $aula) {
                                echo "<tr>";
                                echo "<td>{$aula['nome_aula']}</td>";
                                $diaFormatado = ucfirst($aula['dia_aula']);
                                echo "<td>{$diaFormatado}</td>";
                                $horarioFormatado = date('H:i', strtotime($aula['horario_aula']));
                                echo "<td>{$horarioFormatado}</td>";
                                echo "<td>{$aula['professor_aula']}</td>";
                                echo "<td>{$aula['local_aula']}</td>";
                                echo "<td>
                                    <button class='conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 editar-btn'
                                            data-id='{$aula['id']}'
                                            data-nome_aula='{$aula['nome_aula']}'
                                            data-dia_aula='{$aula['dia_aula']}'
                                            data-horario_aula='{$horarioFormatado}'
                                            data-professor_aula='{$aula['professor_aula']}'
                                            data-local_aula='{$aula['local_aula']}'>
                                        EDITAR
                                    </button>
                                </td>";
                                echo "</tr>";
                            }
                        } else {
                        
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- CADASTRO -->
<div class="modal fade" id="userForm">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" id="formulario-cadastrar" action="cadastrar.php">
                <div class="modal-header">
                    <h4 class="modal-title">CADASTRO DE AULA</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="inputField">
                        <div class="mb-3">
                            <label class="form-label">Nome da Aula:</label>
                            <input type="text" name="nome_aula" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dia da Semana:</label>
                            <select name="dia_aula" class="form-select" required>
                                <option disabled selected>Selecione</option>
                                <option value="SEGUNDA">Segunda-feira</option>
                                <option value="TERÇA">Terça-feira</option>
                                <option value="QUARTA">Quarta-feira</option>
                                <option value="QUINTA">Quinta-feira</option>
                                <option value="SEXTA">Sexta-feira</option>
                                <option value="SABADO">Sábado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Horário da Aula:</label>
                            <input type="time" name="horario_aula" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Professor:</label>
                            <select name="professor_aula" class="form-select" required>
                                <option disabled selected>Selecione o professor</option>
                                <?php foreach($professores as $professor ): ?>
                                    <option value="<?= $professor['nome'] ?>"><?= $professor['nome'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Local de aula:</label>
                            <select name="local_aula" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o local:</option>
                                <option value="Sala 1">Sala 1</option>
                                <option value="Sala 2">Sala 2</option>
                                <option value="Sala 3">Sala 3</option>
                                <option value="Sala 4">Sala 4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success">SALVAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDITAR -->
<form method="POST" id="formulario-editar" action="editar.php">
    <input type="hidden" name="id" id="editar-id">
    <div class="modal fade" id="editar" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDITAR AULA</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="inputField">
                        <div class="mb-3">
                            <label class="form-label">Nome da Aula:</label>
                            <input type="text" name="nome_aula" id="editar-nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dia da Semana:</label>
                            <select name="dia_aula" id="editar-dia" class="form-select" required>
                               <option disabled selected>Selecione</option>
                                <option value="SEGUNDA">Segunda-feira</option>
                                <option value="TERÇA">Terça-feira</option>
                                <option value="QUARTA">Quarta-feira</option>
                                <option value="QUINTA">Quinta-feira</option>
                                <option value="SEXTA">Sexta-feira</option>
                                <option value="SABADO">Sábado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Horário da Aula:</label>
                            <input type="time" name="horario_aula" id="editar-horario" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Professor:</label>
                            <select name="professor_aula" class="form-select" required>
                                <option disabled selected>Selecione o professor</option>
                                <?php foreach($professores as $professor ): ?>
                                    <option value="<?= $professor['nome'] ?>"><?= $professor['nome'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Local de aula:</label>
                            <select name="local_aula" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o local:</option>
                                <option value="Sala 1">Sala 1</option>
                                <option value="Sala 2">Sala 2</option>
                                <option value="Sala 3">Sala 3</option>
                                <option value="Sala 4">Sala 4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success">SALVAR</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="app.js"></script>
<script>

// PDF Generation Functions
$(".botao-gerar-pdf").on("click", function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    let y = 10;

    doc.setFont("helvetica", "bold");
    doc.setFontSize(16);
    doc.text("Relatório de Aulas", 15, y);
    y += 18;

    const tableBody = document.querySelectorAll("table tbody tr");

    doc.setFont("helvetica", "normal");
    doc.setFontSize(12);

    tableBody.forEach((row) => {
        if (y > doc.internal.pageSize.height - 90) {
            doc.addPage();
            y = 10;
        }

        const nomeAula = row.children[0]?.innerText.trim() || "";
        const diaAula = row.children[1]?.innerText.trim() || "";
        const horarioAula = row.children[2]?.innerText.trim() || "";
        const professor = row.children[3]?.innerText.trim() || "";
        const localAula = row.children[4]?.innerText.trim() || "";

        doc.setFont("helvetica", "bold");
        doc.text(`Nome da Aula: ${nomeAula}`, 15, y); y += 7;
        doc.text(`Dia: ${diaAula}`, 15, y); y += 7;
        doc.text(`Horário: ${horarioAula}`, 15, y); y += 7;
        doc.text(`Professor: ${professor}`, 15, y); y += 7;
        doc.text(`Local: ${localAula}`, 15, y); y += 10;

        // Desenha um retângulo ao redor da ficha
        doc.setDrawColor(0);
        doc.setLineWidth(0.3);
        doc.rect(10, y - 50, 190, 50, "S");

        y += 10;
    });

    // Rodapé
    const date = new Date();
    const formattedDate = date.toLocaleString("pt-BR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
    });

    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(10);
        doc.setFont("helvetica", "normal"); // Define a fonte como normal para o rodapé
        doc.text(formattedDate, 10, doc.internal.pageSize.height - 10);
        const pageText = `Página ${i} de ${pageCount}`;
        const pageTextWidth = doc.getTextWidth(pageText);
        doc.text(
            pageText,
            doc.internal.pageSize.width - pageTextWidth - 10,
            doc.internal.pageSize.height - 10
        );
    }
    doc.save("Relatório Geral de Aulas.pdf");
});

</script>
</body>
</html>
