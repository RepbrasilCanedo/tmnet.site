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
            <span class="title-content">Detalhes do Equipamento</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_equip']) {
                    echo "<a href='" . URLADM . "list-equip/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewEquip'])) {
                    if ($this->data['button']['edit_equip']) {
                        echo "<a href='" . URLADM . "edit-equip/index/" . $this->data['viewEquip'][0]['id_equip'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['delete_equip']) {
                        echo "<a href='" . URLADM . "delete-equip/index/" . $this->data['viewEquip'][0]['id_equip'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
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
            if (!empty($this->data['viewEquip'])) {
                extract($this->data['viewEquip'][0]);
            ?>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id_equip; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo $name_equip; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Tipo: </span>
                    <span class="view-adm-info"><?php echo $name_typ; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Num. Série: </span>
                    <span class="view-adm-info"><?php echo $serie_equip; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Modelo: </span>
                    <span class="view-adm-info"><?php echo $name_modelo; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Marca: </span>
                    <span class="view-adm-info"><?php echo $name_mar; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Empresa: </span>
                    <span class="view-adm-info"><?php echo $nome_fantasia_emp; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Contrato: </span>
                    <span class="view-adm-info"><?php echo $num_cont_equip; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação: </span>
                    <span class="view-adm-info"><?php echo $name_sit; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created)); ?></span>                   
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Modificado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', $modified); ?></span>
                    
                </div>
            <?php
            }
            ?>
        </div>

        
    </div>
</div>
<!-- Fim do conteudo do administrativo -->