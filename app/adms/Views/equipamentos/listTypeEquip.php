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
            <span class="title-content">Listar Tipos de Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_type_equip']) {
                    echo "<a href='" . URLADM . "add-type-equip/index' class='btn-success'>Cadastrar</a>";
                }                
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_type_equip = "";
                    if (isset($valorForm['search_type_equip'])) {
                        $search_type_equip = $valorForm['search_type_equip'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Tipo: </label>
                        <input type="text" name="search_type_equip" id="search_type_equip" class="input-search" placeholder="Pesquisar pelo tipo..." value="<?php echo $search_type_equip; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchTypeEquip" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listtypeequip'] as $type_equip) {
                    extract($type_equip);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php                                    
                                    if ($this->data['button']['view_type_equip']) {
                                        echo "<a href='" . URLADM . "view-type-equip/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_type_equip']) {
                                        echo "<a href='" . URLADM . "edit-type-equip/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_type_equip']) {
                                        echo "<a href='" . URLADM . "delete-type-equip/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este tipo de equipamento?\")'>Apagar</a>";
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