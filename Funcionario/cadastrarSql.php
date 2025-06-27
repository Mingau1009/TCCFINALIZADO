<?php 
function formatarCPFSeguro($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);
    return substr($cpf, 0, 3) . '.*.*-' . substr($cpf, -2);
} 

function formatarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) == 11) {
        return '('.substr($telefone, 0, 2).')'.substr($telefone, 2, 5).'-'.substr($telefone, 7);
    } elseif (strlen($telefone) == 10) {
        return '('.substr($telefone, 0, 2).')'.substr($telefone, 2, 4).'-'.substr($telefone, 6);
    }
    return $telefone;
}
?>

<tbody>
<?php 
// Recebe status da URL, padrão para ativos
$status = isset($_GET['status']) ? $_GET['status'] : 1;

// Começa a query base
$sql = "SELECT * FROM funcionario WHERE ativo = :status";

// Se tem pesquisa, adiciona filtro
if(!empty($pesquisa)){
    $sql .= " AND nome LIKE :pesquisa";
}

// Ordenação por data_matricula
if($ordenar == "ASC"){
    $sql .= " ORDER BY data_matricula ASC";
}else if($ordenar == "DESC"){
    $sql .= " ORDER BY data_matricula DESC";
}

$stmt = Db::conexao()->prepare($sql);
$stmt->bindParam(':status', $status, PDO::PARAM_INT);

if(!empty($pesquisa)){
    $likePesquisa = "%$pesquisa%";
    $stmt->bindParam(':pesquisa', $likePesquisa, PDO::PARAM_STR);
}

$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<?php foreach ($funcionarios as $funcionario) { ?>
    <tr>
        <td>
            <?php if($funcionario->ativo == 1) { ?>
                <span class="badge bg-success">ATIVO</span>
            <?php } else { ?>
                <span class="badge bg-danger">INATIVO</span>
            <?php } ?>
        </td>
        <td><?php echo $funcionario->nome; ?></td>
        <td><?php echo date('d/m/Y', strtotime($funcionario->data_nascimento)); ?></td>
        <td><?php echo formatarCPFSeguro($funcionario->cpf); ?></td>
        <td><?php echo formatarTelefone($funcionario->telefone); ?></td>
        <td><?php echo $funcionario->endereco; ?></td>
        <td><?php echo $funcionario->turno_disponivel; ?></td>  
        <td><?php echo date('d/m/Y', strtotime($funcionario->data_matricula)); ?></td>
        <td class="conteudo-esconder-pdf">
            <button 
                class="conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 botao-selecionar-matricula"
                data-id="<?php echo $funcionario->id; ?>"
                data-nome="<?php echo $funcionario->nome; ?>"
                data-data_nascimento="<?php echo $funcionario->data_nascimento; ?>"
                data-cpf="<?php echo $funcionario->cpf; ?>"
                data-telefone="<?php echo $funcionario->telefone; ?>"
                data-endereco="<?php echo $funcionario->endereco; ?>"
                data-turno_disponivel="<?php echo $funcionario->turno_disponivel; ?>"
                data-data_matricula="<?php echo $funcionario->data_matricula; ?>"
                data-ativo="<?php echo $funcionario->ativo; ?>">
                EDITAR
            </button>
        </td>
    </tr>
<?php } ?>
</tbody>
