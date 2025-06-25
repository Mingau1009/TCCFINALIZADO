<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="style.css">
<script src="../Login/inactivity.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<link rel="stylesheet" href="../Sidebar/style.css">

<?php
include("../Classe/Conexao.php");
include("../Sidebar/index.php");
include("../Classe/Log.php");

// Verifica erros de conexão
if (!Db::conexao()) {
    die("Erro na conexão com o banco de dados");
}
?>

<section class="p-3" style="margin-left:85px;">
    <h2 class="mb-4">Logs do Sistema</h2>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>Data/Hora</th>
                            <th>Usuário</th>
                            <th>Ação</th>
                            <th>Tabela</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            $sql = "SELECT l.* FROM logs l ORDER BY l.data_hora DESC LIMIT 100";
                            $stmt = Db::conexao()->query($sql);
                            $logs = $stmt->fetchAll(PDO::FETCH_OBJ);
                            
                            foreach ($logs as $log) {
                                echo "<tr>";
                                echo "<td>" . date('d/m/Y H:i:s', strtotime($log->data_hora)) . "</td>";
                                echo "<td>" . htmlspecialchars($log->usuario_email ?? 'Sistema') . "</td>";
                                echo "<td>" . htmlspecialchars($log->acao) . "</td>";
                                echo "<td>" . htmlspecialchars($log->tabela_afetada) . "</td>";
                                echo "<td><button class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#detalhesLog{$log->id}'>Ver</button></td>";
                                echo "</tr>";
                                
                                // Modal de detalhes
                                echo '<div class="modal fade" id="detalhesLog'.$log->id.'" tabindex="-1" aria-hidden="true">';
                                echo '<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">';
                                echo '<div class="modal-content">';
                                echo '<div class="modal-header bg-primary text-white">';
                                echo '<h5 class="modal-title">Detalhes do Log #'.$log->id.'</h5>';
                                echo '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>';
                                echo '</div>';
                                echo '<div class="modal-body">';
                                
                                if ($log->dados_anteriores) {
                                    echo '<div class="mb-4">';
                                    echo '<h6 class="fw-bold text-primary">Dados Anteriores:</h6>';
                                    echo '<div class="bg-light p-3 rounded border">';
                                    echo '<pre>'.htmlspecialchars(print_r(json_decode($log->dados_anteriores, true), true)).'</pre>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                
                                if ($log->dados_novos) {
                                    echo '<div>';
                                    echo '<h6 class="fw-bold text-primary">Dados Novos:</h6>';
                                    echo '<div class="bg-light p-3 rounded border">';
                                    echo '<pre>'.htmlspecialchars(print_r(json_decode($log->dados_novos, true), true)).'</pre>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                
                                echo '</div>';
                                echo '<div class="modal-footer">';
                                echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='5' class='text-danger'>Erro ao carregar logs: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>