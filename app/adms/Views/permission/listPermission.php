<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>

<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Permissões do Nível de Acesso <?php if(isset($this->data['viewAccessLevel'][0]['name'])){echo $this->data['viewAccessLevel'][0]['name'];} ?> </span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_access_levels']) {
                    echo "<a href='" . URLADM . "list-access-levels/index' class='btn-info'>Listar Nível de Acesso</a>";
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
                        <input type="text" name="search_name" id="search_name" class="input-search" placeholder="Pesquisar pelo nome da página" value="<?php echo $search_name; ?>">
                    </div>

                    <div class="column margin-top-search-one">
                        <button type="submit" name="SendSearchPermission" class="btn-info" value="Pesquisar">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>  
        <?php echo $this->data['pagination']; ?>
        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Página</th>
                    <th class="list-head-content table-sm-none">Ordem</th>
                    <th class="list-head-content table-sm-none">Permissão</th>
                    <th class="list-head-content table-md-none">Menu</th>
                    <th class="list-head-content table-md-none">Dropdown</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listPermission'] as $permission) {
                    extract($permission);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name_page; ?></td>
                        <td class="list-body-content table-sm-none"><?php echo $order_level_page; ?></td>
                        <td class="list-body-content table-sm-none">
                            <?php
                            if ($permission == 1) {
                                echo "<a href='" . URLADM . "edit-permission/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-success'>Liberado</span></a>";
                            } else {
                                echo "<a href='" . URLADM . "edit-permission/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-danger'>Bloqueado</span></a>";
                            }
                            ?>
                        </td>
                        <td class="list-body-content table-md-none">
                            <?php
                            if ($print_menu == 1) {
                                echo "<a href='" . URLADM . "edit-print-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-success'>Liberado</span></a>";
                            } else {
                                echo "<a href='" . URLADM . "edit-print-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-danger'>Bloqueado</span></a>";
                            }
                            ?>
                        </td>
                        <td class="list-body-content table-md-none">
                            <?php
                            if ($dropdown == 1) {
                                echo "<a href='" . URLADM . "edit-dropdown-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-success'>Sim</span></a>";
                            } else {
                                echo "<a href='" . URLADM . "edit-dropdown-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><span class='text-danger'>Não</span></a>";
                            }
                            ?>
                        </td>
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['order_page_menu']) {
                                        echo "<a href='" . URLADM . "order-page-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><i class='fa-solid fa-angles-up'></i> Ordem</a>";
                                    }

                                    if ($this->data['button']['edit_page_menu']) {
                                        echo "<a href='" . URLADM . "edit-page-menu/index/$id?&level=$adms_access_level_id&pag=" . $this->data['pag'] . "'><i class='fa-solid fa-pen-to-square'></i> Editar</a>";
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