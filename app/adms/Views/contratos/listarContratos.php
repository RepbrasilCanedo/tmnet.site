<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);
}
?>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Listagem de Contratos</span>
        <a href="<?= URL ?>contratos/cadastrar" class="btn btn-outline-primary btn-sm">Novo Contrato</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Título</th>
                        <th>Vigência</th>
                        <th>Cobertura</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->dados['listContracts'])): ?>
                        <?php foreach ($this->dados['listContracts'] as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= $row['cliente_nome'] ?></td>
                                <td><?= $row['nome'] ?></td>
                                <td><?= date('d/m/Y', strtotime($row['data_inicio'])) ?> a <?= date('d/m/Y', strtotime($row['data_fim'])) ?></td>
                                <td>
                                    <?php if ($row['cobre_todos_equipamentos']): ?>
                                        <span class="badge badge-info">Total</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Parcial</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= URL ?>contratos/equipamentos/<?= $row['id'] ?>" class="btn btn-sm btn-dark" title="Gerenciar Equipamentos">
                                        <i class="fas fa-desktop"></i> Equipamentos
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Nenhum contrato encontrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>