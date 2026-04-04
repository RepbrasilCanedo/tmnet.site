<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Contratos</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['add_contratos'])): ?>
                    <a href="<?= URLADM ?>add-contratos/index" class="btn-success">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <div class="column">
                        <label class="title-input-search">Nome do Contrato: </label>
                        <input type="text" name="search_name" id="search_name" class="input-search" 
                               placeholder="Pesquisar pelo nome..." 
                               value="<?= $this->data['form']['search_name'] ?? '' ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="listSearchContratos" class="btn-info" value="Pesquisar">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <?php if (!empty($this->data['listContratos'])): ?>
            <table class="table table-hover table-list">
                <thead class="list-head">
                    <tr>
                        <th class="list-head-content">ID</th>
                        <th class="list-head-content">Cliente</th>
                        <th class="list-head-content">Nome / Descrição</th>
                        <th class="list-head-content table-sm-none">Tipo</th>
                        <th class="list-head-content table-sm-none">Início</th>
                        <th class="list-head-content table-sm-none">Fim</th>
                        <th class="list-head-content table-sm-none">Status</th>
                        <th class="list-head-content">Ações</th>
                    </tr>
                </thead>
                <tbody class="list-body">
                    <?php foreach ($this->data['listContratos'] as $contrato): ?>
                        <tr>
                            <td class="list-body-content"><?= $contrato['id'] ?></td>
                            <td class="list-body-content"><?= $contrato['nome_fantasia_cli'] ?></td>
                            <td class="list-body-content"><?= htmlspecialchars($contrato['name']) ?></td>
                            <td class="list-body-content table-sm-none"><?= htmlspecialchars($contrato['tipo_nome'] ?? 'Não Definido') ?></td>
                            
                            <td class="list-body-content table-sm-none">
                                <?= !empty($contrato['inicio_contr']) ? date('d/m/Y', strtotime($contrato['inicio_contr'])) : '-' ?>
                            </td>
                            <td class="list-body-content table-sm-none">
                                <?= !empty($contrato['final_contr']) ? date('d/m/Y', strtotime($contrato['final_contr'])) : '-' ?>
                            </td>
                            
                            <td class="list-body-content table-sm-none"><?= htmlspecialchars($contrato['sit_nome'] ?? 'Sem Status') ?></td>
                            
                            <td class="list-body-content">
                                <div class="dropdown-action">
                                    <button onclick="actionDropdown(<?= $contrato['id'] ?>)" class="dropdown-btn-action">Ações</button>
                                    <div id="actionDropdown<?= $contrato['id'] ?>" class="dropdown-action-item">
                                        <?php if (!empty($this->data['button']['view_contratos'])): ?>
                                            <a href="<?= URLADM ?>view-contratos/index/<?= $contrato['id'] ?>">Visualizar</a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($this->data['button']['edit_contratos'])): ?>
                                            <a href="<?= URLADM ?>edit-contratos/index/<?= $contrato['id'] ?>">Editar</a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($this->data['button']['delete_contratos'])): ?>
                                            <a href="<?= URLADM ?>delete-contratos/index/<?= $contrato['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este contrato?')">Apagar</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?= $this->data['pagination'] ?? '' ?>
        <?php else: ?>
            <p class="alert-warning">Nenhum registo encontrado.</p>
        <?php endif; ?>

    </div>
</div>