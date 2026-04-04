<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes do Modelo</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_modelo']) {
                    echo "<a href='" . URLADM . "list-modelo/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewModelo'])) {
                    if ($this->data['button']['edit_modelo']) {
                        echo "<a href='" . URLADM . "edit-modelo/index/" . $this->data['viewModelo'][0]['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['delete_modelo']) {
                        echo "<a href='" . URLADM . "delete-modelo/index/" . $this->data['viewModelo'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
                    }
                }
                ?>
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
            <?php
            if (!empty($this->data['viewModelo'])) {
                extract($this->data['viewModelo'][0]);
            ?>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo $name; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created)); ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Editado: </span>
                    <span class="view-adm-info">
                        <?php
                        if (!empty($modified)) {
                            echo date('d/m/Y H:i:s', strtotime($modified));
                        } ?>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->