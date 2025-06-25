<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://kit.fontawesome.com/af6fbadd15.js" crossorigin="anonymous"></script>
    <title>Área de cadastro de exercício</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../Sidebar/style.css">
    <script src="../Login/inactivity.js"></script>
</head>
<body>

<?php include("../Classe/Conexao.php") ?>
<?php include("../Sidebar/index.php"); ?>

<section class="p-3" style="margin-left:85px;">
    <div class="text-end mb-2 conteudo-esconder-pdf">
        <button class="btn btn-success newUser " data-bs-toggle="modal" data-bs-target="#userForm">
            CADASTRAR <i class="bi bi-people"></i>
        </button>
    </div>

    <form method="get" class="mb-2 conteudo-esconder-pdf" id="form-pesquisa">
    <div class="row">
        <div class="col-md-4">
            <div class="input-group">
                <input name="pesquisa" id="input-pesquisa" type="text" class="form-control" placeholder="Buscar por nome de exercicio..." autocomplete="off">
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

    .table {
        margin-left: 100px;  
        margin-right: 220px; 
        width: 93%;        
    }
</style>

<section class="p-3">
    <div class="row justify-content-center"> <!-- Centraliza a tabela -->
        <div class="col-12">
            <table class="table table-striped table-hover mt-3 text-center table-bordered">
                <thead>
                    <tr>
                        <th style="width: 480px;">NOME DO EXERCÍCIO</th> 
                        <th style="width: 350px;">TIPO</th> 
                        <th style="width: 350px;">GRUPO</th> 
                        <th class="conteudo-esconder-pdf" style="width: 180px;">AJUSTES</th>
                    </tr>
                </thead>
                <tbody>
                <?php $exercicios = Db::conexao()->query("SELECT * FROM `exercicio`")->fetchAll(PDO::FETCH_OBJ); ?>
                <?php foreach ($exercicios as $exercicio) { ?>
                    <tr>
                        <td><?php echo $exercicio->nome; ?></td>
                        <td><?php echo $exercicio->tipo_exercicio; ?></td>
                        <td><?php echo $exercicio->grupo_muscular; ?></td>
                        <td class="conteudo-esconder-pdf">
                        <button 
                            class="conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 botao-selecionar-exercicio"
                            data-id="<?php echo $exercicio->id; ?>"
                            data-nome="<?php echo $exercicio->nome; ?>"
                            data-tipo_exercicio="<?php echo $exercicio->tipo_exercicio; ?>"
                            data-grupo_muscular="<?php echo $exercicio->grupo_muscular; ?>">
                            EDITAR
                        </button>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade" id="userForm">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">CADASTRO DE EXERCICIO</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" id="formulario-cadastrar" action="cadastrar.php">
                <div class="modal-body">
                    <div class="inputField">
                        <div class="mb-3">
                            <label for="exerciseName" class="form-label">Nome do exercício:</label>
                            <input type="text" id="exerciseName" name="nome" class="form-control small-input" required>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseType" class="form-label">Escolha o tipo de treino:</label>
                            <select name="tipo_exercicio" id="exerciseType" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o tipo de treino</option>
                                <option value="MUSCULAÇÃO">MUSCULAÇÃO</option>
                                <option value="CARDIO">CARDIO</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseGroup" class="form-label">Grupo:</label>
                            <select name="grupo_muscular" id="exerciseGroup" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o tipo de treino</option>
                                <option value="ABDÔMEN">ABDÔMEN</option>
                                <option value="CARDIO">CARDIO</option>
                                <option value="DORSAL">DORSAL</option>
                                <option value="MEMBROS INFERIORES">MEMBROS INFERIORES</option>
                                <option value="MEMBROS SUPERIORES">MEMBROS SUPERIORES</option>
                                <option value="BICEPS">BICEPS</option>
                                <option value="TRICEPS">TRICEPS</option>
                                <option value="PEITORAL">PEITORAL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success submit">SALVAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form method="POST" id="formulario-editar" action="editar.php">
    <input type="hidden" name="id" class="form-control">
    <div class="modal fade" id="editar" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">EDITAR</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="inputField">
                        <div class="mb-3">
                            <label for="exerciseName" class="form-label">Nome do exercício:</label>
                            <input type="text" id="exerciseName" name="nome" class="form-control small-input" required>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseType" class="form-label">Escolha o tipo de treino:</label>
                            <select name="tipo_exercicio" id="exerciseType" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o tipo de treino</option>
                                 <option value="MUSCULAÇÃO">MUSCULAÇÃO</option>
                                <option value="CARDIO">CARDIO</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="exerciseGroup" class="form-label">Grupo:</label>
                            <select name="grupo_muscular" id="exerciseGroup" class="form-select small-select" required>
                                <option value="" disabled selected>Selecione o tipo de treino</option>
                                <option value="ABDÔMEN">ABDÔMEN</option>
                                <option value="CARDIO">CARDIO</option>
                                <option value="DORSAL">DORSAL</option>
                                <option value="MEMBROS INFERIORES">MEMBROS INFERIORES</option>
                                <option value="MEMBROS SUPERIORES">MEMBROS SUPERIORES</option>
                                <option value="BICEPS">BICEPS</option>
                                <option value="TRICEPS">TRICEPS</option>
                                <option value="PEITORAL">PEITORAL</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success submit">SALVAR</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="app.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.botao-gerar-pdf').addEventListener('click', function () {
        const botao = this;
        botao.disabled = true;

        const elementosEsconder = document.querySelectorAll('.conteudo-esconder-pdf');
        elementosEsconder.forEach(el => el.style.display = 'none');

        const tabela = document.querySelector('table');

        html2canvas(tabela, {
            backgroundColor: '#ffffff',
            scale: 2
        }).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF('landscape', 'pt', 'a4');

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();
            const margin = 20;
            const imgWidth = pageWidth - 2 * margin;
            const imgHeight = canvas.height * imgWidth / canvas.width;

            pdf.addImage(imgData, 'PNG', margin, margin, imgWidth, imgHeight);

            const agora = new Date();
            const dia = String(agora.getDate()).padStart(2, '0');
            const mes = String(agora.getMonth() + 1).padStart(2, '0');
            const ano = agora.getFullYear();
            const hora = String(agora.getHours()).padStart(2, '0');
            const minuto = String(agora.getMinutes()).padStart(2, '0');
            const segundo = String(agora.getSeconds()).padStart(2, '0');
            const dataHoraFormatada = `${dia}/${mes}/${ano} ${hora}:${minuto}:${segundo}`;

            const totalPages = pdf.getNumberOfPages();
            pdf.setFontSize(10);
            for (let i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.text(dataHoraFormatada, margin, pageHeight - 10);
                pdf.text(`Página ${i} de ${totalPages}`, pageWidth - margin, pageHeight - 10, { align: 'right' });
            }

            pdf.save(`relatorio-exercicios-${dia}-${mes}-${ano}.pdf`);

            elementosEsconder.forEach(el => el.style.display = '');
            botao.disabled = false;
        });
    });
});

$(document).ready(function() {
    // Função para filtrar a tabela
    function filtrarTabela(termo) {
        termo = termo.toLowerCase();
        $('table tbody tr').each(function() {
            const nomeExercicio = $(this).find('td:first').text().toLowerCase();
            if (nomeExercicio.includes(termo)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    // Evento de input no campo de pesquisa
    $('#input-pesquisa').on('input', function() {
        const termo = $(this).val();
        filtrarTabela(termo);
    });

    // Se quiser manter a funcionalidade de submit do formulário também
    $('#form-pesquisa').on('submit', function(e) {
        e.preventDefault();
        const termo = $('#input-pesquisa').val();
        filtrarTabela(termo);
    });
});

</script>

</body>
</html>