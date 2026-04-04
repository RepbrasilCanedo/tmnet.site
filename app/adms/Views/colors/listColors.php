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
<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Cores</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_colors']) {
                    echo "<a href='" . URLADM . "add-colors/index' class='btn-success'>Cadastrar</a>";
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

                    <?php
                    $search_color = "";
                    if (isset($valorForm['search_color'])) {
                        $search_color = $valorForm['search_color'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Cor: </label>
                        <input type="text" name="search_color" id="search_color" class="input-search" placeholder="Pesquisar pela cor..." value="<?php echo $search_color; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchColor" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                    <th class="list-head-content table-sm-none">Cor</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listColors'] as $colors) {
                    extract($colors);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        <td class="list-body-content table-sm-none">
                            <?php echo "<span style='color: $color'>$color</span>"; ?>
                        </td>
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_colors']) {
                                        echo "<a href='" . URLADM . "view-colors/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_colors']) {
                                        echo "<a href='" . URLADM . "edit-colors/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_colors']) {
                                        echo "<a href='" . URLADM . "delete-colors/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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