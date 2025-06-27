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
// Receber filtros
$status = isset($_GET['status']) ? $_GET['status'] : 1; // padrão: ativos

$sql = "SELECT * FROM `aluno` WHERE `ativo` = :status";

if ($pesquisa) {
    $sql .= " AND `nome` LIKE :pesquisa";
}

$sql .= $ordenar == "DESC" ? " ORDER BY `data_matricula` DESC" : " ORDER BY `data_matricula` ASC";

$stmt = Db::conexao()->prepare($sql);
$stmt->bindValue(':status', $status, PDO::PARAM_INT);
if ($pesquisa) {
    $stmt->bindValue(':pesquisa', "%{$pesquisa}%", PDO::PARAM_STR);
}

$stmt->execute();
$alunos = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<?php foreach ($alunos as $aluno) { ?>
<tr>
    <td>
        <?php if ($aluno->ativo == 1) { ?>
            <span class="badge bg-success">ATIVO</span>
        <?php } else { ?>
            <span class="badge bg-danger">INATIVO</span>
        <?php } ?>
    </td>
    <td><?php echo $aluno->nome; ?></td>
    <td>
        <?php echo $aluno->data_nascimento ? date('d/m/Y', strtotime($aluno->data_nascimento)) : '--'; ?>
    </td>
    <td><?php echo formatarCPFSeguro($aluno->cpf); ?></td>
    <td><?php echo formatarTelefone($aluno->telefone); ?></td>
    <td><?php echo $aluno->endereco; ?></td>
    <td><?php echo $aluno->frequencia; ?></td>
    <td><?php echo $aluno->objetivo; ?></td>
    <td><?php echo date('d/m/Y', strtotime($aluno->data_matricula)); ?></td>
    <td class="conteudo-esconder-pdf">
        <button 
            class="conteudo-esconder-pdf btn btn-primary btn-sm p-0 ps-2 pe-2 botao-selecionar-matricula"
            data-id="<?php echo $aluno->id; ?>"
            data-nome="<?php echo $aluno->nome; ?>"
            data-data_nascimento="<?php echo $aluno->data_nascimento; ?>"
            data-cpf="<?php echo $aluno->cpf; ?>"
            data-telefone="<?php echo $aluno->telefone; ?>"
            data-endereco="<?php echo $aluno->endereco; ?>"
            data-frequencia="<?php echo $aluno->frequencia; ?>"
            data-objetivo="<?php echo $aluno->objetivo; ?>"
            data-data_matricula="<?php echo $aluno->data_matricula; ?>"
            data-ativo="<?php echo $aluno->ativo; ?>">
            EDITAR
        </button>
    </td>
</tr>
<?php } ?>
</tbody>
