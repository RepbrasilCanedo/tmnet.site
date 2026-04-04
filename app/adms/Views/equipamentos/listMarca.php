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
            <span class="title-content">Listar Marcas de Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_marca']) {
                    echo "<a href='" . URLADM . "add-marca/index' class='btn-success'>Cadastrar</a>";
                }                
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_marca = "";
                    if (isset($valorForm['search_marca'])) {
                        $search_marca = $valorForm['search_marca'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Marca: </label>
                        <input type="text" name="search_marca" id="search_marca" class="input-search" placeholder="Pesquisar pela marca..." value="<?php echo $search_marca; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchMarca" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                foreach ($this->data['listmarca'] as $marca) {
                    extract($marca);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php                                    
                                    if ($this->data['button']['view_marca']) {
                                        echo "<a href='" . URLADM . "view-marca/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_marca']) {
                                        echo "<a href='" . URLADM . "edit-marca/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_marca']) {
                                        echo "<a href='" . URLADM . "delete-marca/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir esta marca de equipamento?\")'>Apagar</a>";
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