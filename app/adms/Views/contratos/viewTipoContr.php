<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes do Tipo de Contrato</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['list_tipo_contr'])): ?>
                    <a href="<?= URLADM ?>list-tipo-contr/index" class="btn-info">Listar</a>
                <?php endif; ?>
                
                <?php if (!empty($this->data['button']['edit_tipo_contr']) && !empty($this->data['viewTipoContr']['id'])): ?>
                    <a href="<?= URLADM ?>edit-tipo-contr/index/<?= $this->data['viewTipoContr']['id'] ?>" class="btn-warning">Editar</a>
                <?php endif; ?>
                
                <?php if (!empty($this->data['button']['delete_tipo_contr']) && !empty($this->data['viewTipoContr']['id'])): ?>
                    <a href="<?= URLADM ?>delete-tipo-contr/index/<?= $this->data['viewTipoContr']['id'] ?>" class="btn-danger" onclick="return confirm('Tem certeza que deseja excluir este registro?')">Apagar</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <div class="content-adm">
            <?php if (!empty($this->data['viewTipoContr'])): ?>
                <div class="view-det-list">
                    <span class="view-det-title">ID:</span>
                    <span class="view-det-info"><?= $this->data['viewTipoContr']['id'] ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Nome:</span>
                    <span class="view-det-info"><?= $this->data['viewTipoContr']['name'] ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Status:</span>
                    <span class="view-det-info"><?= $this->data['viewTipoContr']['name_sit'] ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Criado em:</span>
                    <span class="view-det-info">
                        <?= !empty($this->data['viewTipoContr']['created']) ? date('d/m/Y H:i:s', strtotime($this->data['viewTipoContr']['created'])) : '' ?>
                    </span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Modificado em:</span>
                    <span class="view-det-info">
                        <?= !empty($this->data['viewTipoContr']['modified']) ? date('d/m/Y H:i:s', strtotime($this->data['viewTipoContr']['modified'])) : '' ?>
                    </span>
                </div>
            <?php else: ?>
                <p class="alert-danger">Erro: Nenhum detalhe encontrado para este registro.</p>
            <?php endif; ?>
        </div>
    </div>
</div>