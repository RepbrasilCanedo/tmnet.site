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
            <span class="title-content">Listar Modelos de Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_modelo']) {
                    echo "<a href='" . URLADM . "add-modelo/index' class='btn-success'>Cadastrar</a>";
                }                
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_modelo = "";
                    if (isset($valorForm['search_modelo'])) {
                        $search_modelo = $valorForm['search_modelo'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Modelo: </label>
                        <input type="text" name="search_modelo" id="search_modelo" class="input-search" placeholder="Pesquisar pelo modelo..." value="<?php echo $search_modelo; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchModelo" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                foreach ($this->data['listmodelo'] as $modelo) {
                    extract($modelo);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php                                    
                                    if ($this->data['button']['view_modelo']) {
                                        echo "<a href='" . URLADM . "view-modelo/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_modelo']) {
                                        echo "<a href='" . URLADM . "edit-modelo/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_modelo']) {
                                        echo "<a href='" . URLADM . "delete-modelo/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este modelo de equipamento?\")'>Apagar</a>";
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