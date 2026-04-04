<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
//var_dump($this->data);
?>

<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Tipos de Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_tip_equi']) {
                    echo "<a href='" . URLADM . "add-tip-equi/index' class='btn-success'>Cadastrar</a>";
                }
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_name = "";
                    if (isset($valorForm['search_name'])) {
                        $search_name = $valorForm['search_name'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Nome: </label>
                        <input type="text" name="search_name" id="search_name" class="input-search" placeholder="Pesquisar pelo nome..." value="<?php echo $search_name; ?>">
                    </div>


                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchTipEqui" class="btn-info" value="Pesquisar">Pesquisar</button>
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
        <table class="table table-hover table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Nome</th>
                    <th class="list-head-content table-sm-none">Situação</th>
                    <?php 
                            if ($_SESSION['adms_access_level_id'] <= 2) {?>
                            <th class="list-head-content table-sm-none">Empresa</th>
                        <?php } ?>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listTipEqui'] as $listTipEqui) {
                    extract($listTipEqui);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id_tip; ?></td>
                        <td class="list-body-content"><?php echo $name_tip; ?></td>                      
                        <td class="list-body-content table-sm-none"> <?php echo $name_sit?></td>
                        <?php 
                            if ($_SESSION['adms_access_level_id'] <= 2) {?>
                            <td class="list-body-content table-sm-none"> <?php echo $nome_fantasia?></td>
                        <?php } ?>
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id_tip; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id_tip; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['edit_tip_equi']) {
                                        echo "<a href='" . URLADM . "edit-tip-equi/index/$id_tip'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_tip_equi']) {
                                        echo "<a href='" . URLADM . "delete-tip-equi/index/$id_tip' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>

        <?php echo $this->data['pagination']; ?>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->