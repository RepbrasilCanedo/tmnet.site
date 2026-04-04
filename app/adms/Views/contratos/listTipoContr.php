<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Tipos de Contratos</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['add_tipo_contr'])): ?>
                    <a href="<?= URLADM ?>add-tipo-contr/index" class="btn-success">Cadastrar</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <div class="column">
                        <label class="title-input-search">Nome: </label>
                        <input type="text" name="search_name" id="search_name" class="input-search" 
                               placeholder="Pesquisar pelo nome..." 
                               value="<?= $this->data['form']['search_name'] ?? '' ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="listSearchCTipoContr" class="btn-info" value="Pesquisar">Pesquisar</button>
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

        <?php if (!empty($this->data['listTipoContr'])): ?>
            <table class="table table-hover table-list">
                <thead class="list-head">
                    <tr>
                        <th class="list-head-content">ID</th>
                        <th class="list-head-content">Nome</th>
                        <th class="list-head-content table-sm-none">Status</th>
                        <th class="list-head-content">Ações</th>
                    </tr>
                </thead>
                <tbody class="list-body">
                    <?php foreach ($this->data['listTipoContr'] as $contrato): ?>
                        <tr>
                            <td class="list-body-content"><?= $contrato['id'] ?></td>
                            <td class="list-body-content"><?= $contrato['name'] ?></td>
                            <td class="list-body-content table-sm-none"><?= $contrato['name_sit'] ?></td>
                            <td class="list-body-content">
                                <div class="dropdown-action">
                                    <button onclick="actionDropdown(<?= $contrato['id'] ?>)" class="dropdown-btn-action">Ações</button>
                                    <div id="actionDropdown<?= $contrato['id'] ?>" class="dropdown-action-item">
                                        <?php if (!empty($this->data['button']['view_tipo_contr'])): ?>
                                            <a href="<?= URLADM ?>view-tipo-contr/index/<?= $contrato['id'] ?>">Visualizar</a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($this->data['button']['edit_tipo_contr'])): ?>
                                            <a href="<?= URLADM ?>edit-tipo-contr/index/<?= $contrato['id'] ?>">Editar</a>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($this->data['button']['delete_tipo_contr'])): ?>
                                            <a href="<?= URLADM ?>delete-tipo-contr/index/<?= $contrato['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Apagar</a>
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
            <p class="alert-warning">Nenhum registro encontrado.</p>
        <?php endif; ?>

    </div>
</div>