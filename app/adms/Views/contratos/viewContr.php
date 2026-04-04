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
            <span class="title-content">Detalhes do Contrato</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_contr']) {
                    echo "<a href='" . URLADM . "list-contr/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewContr'])) {
                    if ($this->data['button']['edit_contr']) {
                        echo "<a href='" . URLADM . "edit-contr/index/" . $this->data['viewContr'][0]['id_cont'] . "' class='btn-warning'>Editar</a> ";
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
            if (!empty($this->data['viewContr'])) {
                extract($this->data['viewContr'][0]);
            ?>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id_cont; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Tipo: </span>
                    <span class="view-adm-info"><?php echo $name_cont; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação: </span>
                    <span class="view-adm-info"><?php echo $situacao; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created)); ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Modificado: </span>
                    <span class="view-adm-info"><?php if (!empty($modified)){echo date('d/m/Y H:i:s', strtotime($modified));}?></span>
                </div>
            <?php
            }
            ?>
            <?php if (isset($anexo)) { ?>
                <div class="column">
                    <h1 class="p-3" style="text-align: center;"> <?php if (isset($clie_cont)) {echo $clie_cont;} ?></h1>
                    <embed src="<?php echo URL; ?>adm/app/adms/assets/arquivos/contratos/<?= $id ?>\<?= $anexo ?>" width=100% height=900>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->