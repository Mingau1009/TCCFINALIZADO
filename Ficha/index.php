<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../Sidebar/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../Login/inactivity.js"></script>

    <title>ÁREA DE CADASTRO DE FICHA</title>

    <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include("../Classe/Conexao.php") ?>

<?php include("../Sidebar/index.php"); ?>

<div class="container">

    <?php $alunos = Db::conexao()->query("SELECT * FROM `aluno` ORDER BY `nome` ASC")->fetchAll(PDO::FETCH_OBJ);?>
    <?php $exercicios = Db::conexao()->query("SELECT * FROM `exercicio` ORDER BY `nome` ASC")->fetchAll(PDO::FETCH_OBJ);?>
    <?php $fichas = Db::conexao()->query("SELECT `ficha`.*, `aluno`.`nome` AS aluno_nome FROM `ficha` INNER JOIN `aluno` ON `aluno`.`id` = `ficha`.`aluno_id`")->fetchAll(PDO::FETCH_OBJ); ?>

<section class="p-3" style="margin-left:85px;">
    <div class="text-end conteudo-esconder-pdf">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadastrar">
            CADASTRAR <i class="bi bi-people"></i>
        </button>
    </div>

    <br>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" id="pesquisa-aluno" class="form-control" placeholder="Buscar por nome do aluno...">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
            </div>
        </div>
        <div class="col-md-6 text-end conteudo-esconder-pdf">
            <button class="btn btn-danger botao-gerar-pdf" onclick="gerarPDF()">
                <i class="bi bi-file-earmark-pdf"></i> GERAR PDF
            </button>
        </div>
    </div>
</section>

<table class="table table-striped table-hover mt-3 text-center table-bordered">
    <thead>
        <tr>
            <th>ALUNO</th>
            <th>DIA DE TREINO</th>
            <th>EXERCÍCIOS</th>
            <th class="conteudo-esconder-pdf">AJUSTES</th>
        </tr>
    </thead>
    <tbody>
        
        <?php foreach ($fichas as $ficha) { ?>
            <?php $exerciciosFicha = Db::conexao()->query("SELECT `exercicio`.`nome` AS exercicio_nome, `ficha_exercicio`.`num_series`, `ficha_exercicio`.`num_repeticoes`, `ficha_exercicio`.`tempo_descanso` FROM `ficha_exercicio` INNER JOIN `exercicio` ON `exercicio`.`id` = `ficha_exercicio`.`exercicio_id` WHERE `ficha_exercicio`.`ficha_id` = {$ficha->id} ORDER BY `exercicio`.`nome` ASC")->fetchAll(PDO::FETCH_OBJ);?>
            <tr>
                <td><?php echo $ficha->aluno_nome; ?></td>
                <td><?php echo $ficha->dia_treino; ?></td>
                <td>
                    <ol>
                        <?php foreach ($exerciciosFicha as $exercicioFicha) { ?>
                            <li><?php echo $exercicioFicha->exercicio_nome; ?> (Séries: <?php echo $exercicioFicha->num_series; ?>, Repetições: <?php echo $exercicioFicha->num_repeticoes; ?>, Descanso: <?php echo $exercicioFicha->tempo_descanso; ?>)</li>
                        <?php } ?>
                    </ol>
                </td>
                <td class="conteudo-esconder-pdf">
                    <button 
                        class="conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 botao-selecionar-ficha"
                        data-id="<?php echo $ficha->id; ?>"
                        data-nome="<?php echo $ficha->nome; ?>"
                        data-dia_treino="<?php echo $ficha->dia_treino; ?>">
                        EDITAR
                    </button>
                    <button 
                        class="btn btn-danger btn-sm p-0 ps-2 pe-2 gerar-pdf-aluno"
                        data-aluno="<?php echo $ficha->aluno_nome; ?>"
                    >
                        PDF <i class=""></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<form method="POST" id="formulario-cadastrar-ficha">
    <div class="modal fade" id="cadastrar" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">FICHA DE TREINO</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>ALUNO</label>
                            <select name="aluno_id" class="form-control" required>
                                <option value="">SELECIONE...</option>
                                <?php foreach($alunos as $aluno) { ?>
                                    <option value="<?php echo $aluno->id; ?>"><?php echo $aluno->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>DIA DO TREINO</label>
                            <select class="form-control" name="dia_treino" required>
                                <option value="">SELECIONE...</option>
                                <option value="SEGUNDA">Segunda</option>
                                <option value="TERCA">Terça</option>
                                <option value="QUARTA">Quarta</option>
                                <option value="QUINTA">Quinta</option>
                                <option value="SEXTA">Sexta</option>
                                <option value="SABADO">Sábado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2 bg-light pt-2 pb-2">
                        <div class="col-md-12">
                            <label>EXERCÍCIO</label>
                            <select name="exercicio_id" class="form-control">
                                <option data-nome="" value="">SELECIONE...</option>
                                <?php foreach($exercicios as $exercicio) { ?>
                                    <option data-nome="<?php echo $exercicio->nome; ?>" value="<?php echo $exercicio->id ?>"><?php echo $exercicio->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>
    
                        <div class="col-md-6">
                            <label>Nº SÉRIES</label>
                            <input type="number" name="num_series" class="form-control">
                        </div>
    
                        <div class="col-md-6">
                            <label>Nº REPETIÇÕES</label>
                            <input type="number" name="num_repeticoes" class="form-control">
                        </div>
    
                        <div class="col-md-12">
                            <label>TEMPO DESCANO</label>
                            <div class="input-group">
                                <input type="number" name="tempo_descanso" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary botao-cadastro-ficha-lista-exercicios">ADICIONAR EXERCÍCIO</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>EXERCÍCIO</th>
                                        <th>Nº SÉRIES</th>
                                        <th>Nº REPETIÇÕES</th>
                                        <th>TEMPO DESCANO</th>
                                    </tr>
                                </thead>
                                <tbody id="cadastro-ficha-lista-exercicios"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success submit">CADASTRAR</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="POST" id="formulario-editar-ficha" action="editar.php">
    <input type="hidden" name="id">
    <div class="modal fade" id="editar" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">FICHA DE TREINO</h4>
                </div>
                <div class="modal-body">
                    <div class="row">            
                        <div class="col-md-6">
                            <label>DIA DO TREINO</label>
                            <select class="form-control" name="dia_treino" required>
                                <option value="">SELECIONE...</option>
                                <option value="SEGUNDA">Segunda</option>
                                <option value="TERCA">Terça</option>
                                <option value="QUARTA">Quarta</option>
                                <option value="QUINTA">Quinta</option>
                                <option value="SEXTA">Sexta</option>
                                <option value="SABADO">Sábado</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mt-2 bg-light pt-2 pb-2">
                        <div class="col-md-12">
                            <label>EXERCÍCIO</label>
                            <select name="exercicio_id" class="form-control">
                                <option data-nome="" value="">SELECIONE...</option>
                                <?php foreach($exercicios as $exercicio) { ?>
                                    <option data-nome="<?php echo $exercicio->nome; ?>" value="<?php echo $exercicio->id ?>"><?php echo $exercicio->nome; ?></option>
                                <?php } ?>
                            </select>
                        </div>
    
                        <div class="col-md-6">
                            <label>Nº SÉRIES</label>
                            <input type="number" name="num_series" class="form-control">
                        </div>
    
                        <div class="col-md-6">
                            <label>Nº REPETIÇÕES</label>
                            <input type="number" name="num_repeticoes" class="form-control">
                        </div>
    
                        <div class="col-md-12">
                            <label>TEMPO DESCANO</label>
                            <div class="input-group">
                                <input type="number" name="tempo_descanso" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary botao-editar-ficha-lista-exercicios">ADICIONAR EXERCÍCIO</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <table class="table table-sm table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>EXERCÍCIO</th>
                                        <th>Nº SÉRIES</th>
                                        <th>Nº REPETIÇÕES</th>
                                        <th>TEMPO DESCANO</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="editar-ficha-lista-exercicios"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                    <button type="submit" class="btn btn-success">EDITAR</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="app.js"></script>

<script>
    window.onload = function () {
    const { jsPDF } = window.jspdf;

    window.gerarPDF = function () {
      const doc = new jsPDF();
      let y = 10;

      doc.setFont("helvetica", "bold");
      doc.setFontSize(16);
      doc.text("Fichas de Treino", 10, y);
      y += 12;

      const tableBody = document.querySelectorAll("table tbody tr");

      doc.setFont("helvetica", "normal");
      doc.setFontSize(12);

      tableBody.forEach((row, idx) => {
        if (y > doc.internal.pageSize.height - 90) {
            doc.addPage();
            y = 10;
        }

        const startY = y;
        const aluno = row.children[0]?.innerText.trim() || "";
        const dia = row.children[1]?.innerText.trim() || "";
        const listaExercicios = row.children[2]?.querySelectorAll("li") || [];

        doc.setFont("helvetica", "bold");
        doc.setFontSize(12);
        doc.text(`Aluno: ${aluno}`, 15, y); y += 7;
        doc.text(`Dia de Treino: ${dia}`, 15, y); y += 7;
        doc.text(`Exercícios`, 15, y); y += 6;

        doc.setFont("helvetica", "normal");
        listaExercicios.forEach((li) => {
            if (y > doc.internal.pageSize.height - 20) {
            doc.addPage();
            y = 10;
            }

            const textoCompleto = li.innerText.trim();
            
            // Extrair nome do exercício e detalhes (séries, repetições, descanso)
            const match = textoCompleto.match(/^(.+?)\s*\(Séries:\s*(\d+),\s*Repetições:\s*(\d+),\s*Descanso:\s*(\d+)\)$/);
            
            if (match) {
              const [, nomeExercicio, series, repeticoes, descanso] = match;
              doc.text(`- ${nomeExercicio}`, 20, y);
              y += 5;
              doc.text(`  Séries: ${series} | Repetições: ${repeticoes} | Descanso: ${descanso}`, 25, y);
              y += 7;
            } else {
              // Fallback caso o formato não seja reconhecido
              doc.text(`- ${textoCompleto}`, 20, y);
              y += 6;
            }
        });

        const endY = y + 2;

        // ✅ Desenha um retângulo ao redor da ficha
        doc.setDrawColor(0);
        doc.setLineWidth(0.3);
        doc.rect(10, startY - 10, 190, endY - startY + 15, "S");

        y = endY + 10;
        });


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
        doc.text(formattedDate, 10, doc.internal.pageSize.height - 10);
        const pageText = `Página ${i} de ${pageCount}`;
        const pageTextWidth = doc.getTextWidth(pageText);
        doc.text(
          pageText,
          doc.internal.pageSize.width - pageTextWidth - 10,
          doc.internal.pageSize.height - 10
        );
      }

      doc.save("fichas_treino.pdf");
    };
  };

</script>
</body>
</html>
