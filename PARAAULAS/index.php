<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../Sidebar/style.css">
    <script src="../Login/inactivity.js"></script>
    <title>ÁREA DE CADASTRO DE FICHA</title>

    <link rel="stylesheet" href="style.css">
    <style>
        .table-responsive {
            overflow-x: auto;
        }
        .conteudo-esconder-pdf {
            display: block !important;
        }
        @media print {
            .conteudo-esconder-pdf {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<?php include("../Classe/Conexao.php") ?>

<?php include("../Sidebar/index.php"); ?>

<div class="container">
    <?php $nome_aulas = Db::conexao()->query("SELECT * FROM `criar_aula` ORDER BY `nome_aula` ASC")->fetchAll(PDO::FETCH_OBJ);?>
    <?php $alunos = Db::conexao()->query("SELECT * FROM `aluno` WHERE ativo = 1")->fetchAll(PDO::FETCH_OBJ);?>
    <?php $exercicios = Db::conexao()->query("SELECT * FROM `exercicio` ORDER BY `nome` ASC")->fetchAll(PDO::FETCH_OBJ);?>
    <?php $aulas = Db::conexao()->query("SELECT ca.*, GROUP_CONCAT(a.nome SEPARATOR ', ') as alunos_nomes FROM `criar_aula` ca INNER JOIN `evento_aluno` ea ON ca.id = ea.evento_id INNER JOIN `aluno` a ON ea.aluno_id = a.id GROUP BY ca.id ORDER BY ca.nome_aula ASC")->fetchAll(PDO::FETCH_OBJ); ?>

    <section class="p-3" style="margin-left:85px;"></section>

    <br>
    <div class="text-end conteudo-esconder-pdf">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadastrar">
            CADASTRAR <i class="bi bi-people"></i>
        </button>
    </div>   
    <br>
    
    <div class="col-12 text-end conteudo-esconder-pdf">
        <div class="d-inline">
            <button class="btn btn-danger botao-gerar-pdf">
                <i class="bi bi-file-earmark-pdf"></i> GERAR PDF
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover mt-3 text-center table-bordered">
            <thead>
                <tr>
                    <th>NOME DA AULA</th>
                    <th>ALUNOS</th>
                    <th>DIA DA AULA</th>
                    <th>HORÁRIO DA AULA</th>
                    <th>PROFESSOR</th>
                    <th class="conteudo-esconder-pdf">AJUSTES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aulas as $aula) { ?>
                    <tr>
                        <td><?php echo $aula->nome_aula; ?></td>
                        <td><?php echo $aula->alunos_nomes; ?></td>
                        <td><?php echo $aula->dia_aula; ?></td>
                        <td><?php echo $aula->horario_aula; ?></td>
                        <td><?php echo $aula->professor_aula; ?></td>
                        <td class="conteudo-esconder-pdf">
                            <button 
                                class="btn btn-primary btn-sm p-0 ps-2 pe-2 botao-selecionar-aula me-1"
                                data-id="<?php echo $aula->id; ?>"
                                data-nome_aula="<?php echo $aula->nome_aula; ?>"
                                data-dia_aula="<?php echo $aula->dia_aula; ?>"
                                data-horario_aula="<?php echo $aula->horario_aula; ?>"
                                data-professor="<?php echo $aula->professor_aula; ?>"
                                data-alunos="<?php echo $aula->alunos_nomes; ?>"
                            >
                                <i class=""></i> EDITAR
                            </button>
                            <button 
                                class="btn btn-danger btn-sm p-0 ps-2 pe-2 gerar-pdf-aula"
                                data-nome_aula="<?php echo $aula->nome_aula; ?>"
                                data-dia_aula="<?php echo $aula->dia_aula; ?>"
                                data-horario_aula="<?php echo $aula->horario_aula; ?>"
                                data-professor="<?php echo $aula->professor_aula; ?>"
                                data-alunos="<?php echo $aula->alunos_nomes; ?>"
                            >
                                <i class=""></i> PDF
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <form method="POST" id="formulario-cadastrar-aluno-evento">
        <div class="modal fade" id="cadastrar" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">MATRICULA PARA AULA</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>AULA</label>
                                <select name="aula_id" class="form-control" required>
                                    <option value="">SELECIONE...</option>
                                    <?php foreach($nome_aulas as $nome_aula) { ?>
                                        <option value="<?php echo $nome_aula->id; ?>"><?php echo $nome_aula->nome_aula; ?></option>
                                    <?php } ?>
                                </select>
                            </div>          
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>ALUNO</label>
                                <div class="input-group">
                                    <select name="aluno_id" class="form-control" >
                                        <option value="">SELECIONE...</option>
                                        <?php foreach($alunos as $aluno) { ?>
                                            <option value="<?php echo $aluno->id; ?>"><?php echo $aluno->nome; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary botao-cadastro-alunos">ADICIONAR ALUNO</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 bg-light pt-2 pb-2">
                            <div class="col-md-12">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Alunos:</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cadastro-alunos"></tbody>
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

    <form method="POST" id="formulario-editar-aluno-evento">
        <input type="hidden" name="id">
        <div class="modal fade" id="editar" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">EDITAR MATRICULA PARA AULA</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>AULA</label>
                                <select name="aula_id" class="form-control" required disabled>
                                    <option value="">SELECIONE...</option>
                                    <?php foreach($nome_aulas as $nome_aula) { ?>
                                        <option value="<?php echo $nome_aula->id; ?>"><?php echo $nome_aula->nome_aula; ?></option>
                                    <?php } ?>
                                </select>
                            </div>          
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label>ALUNO</label>
                                <div class="input-group">
                                    <select name="aluno_id" class="form-control" >
                                        <option value="">SELECIONE...</option>
                                        <?php foreach($alunos as $aluno) { ?>
                                            <option value="<?php echo $aluno->id; ?>"><?php echo $aluno->nome; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary botao-editar-alunos">ADICIONAR ALUNO</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2 bg-light pt-2 pb-2">
                            <div class="col-md-12">
                                <table class="table table-sm table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Alunos:</th>
                                        </tr>
                                    </thead>
                                    <tbody id="editar-alunos"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">FECHAR</button>
                        <button type="submit" class="btn btn-success submit">EDITAR</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="app.js"></script>
  
</body>
</html>
