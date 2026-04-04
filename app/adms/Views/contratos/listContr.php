<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];


}
//echo "<pre>";
//var_dump($this->data);

?>

<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Contratos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_contr']) {
                    echo "<a href='" . URLADM . "add-contr/index' class='btn-success'>Cadastrar</a> ";
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
        <?php 
        if (isset($_SESSION['resultado'])) {
            echo "Total de contratos Cadastrados:  " . $_SESSION['resultado'];
        }
        ?>
        <?php ?>
        <table class="table table-hover table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">Tipo</th>
                    <th class="list-head-content">Situação</th>
                    <th class="list-head-content">Ação</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listContr'] as $contr) {
                    extract($contr);                   
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $name_cont; ?></td>
                        <td class="list-body-content"><?php echo $situacao; ?></td>

                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id_cont; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id_cont; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_contr']) {
                                        echo "<a href='" . URLADM . "view-contr/index/$id_cont'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_contr']) {
                                        echo "<a href='" . URLADM . "edit-contr/index/$id_cont'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_contr']) {
                                        echo "<a href='" . URLADM . "delete-contr/index/$id_cont' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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
        <?php 
        if (isset($_SESSION['resultado'])) {
            echo "Total de contratos Cadastrados:  " . $_SESSION['resultado'];
            unset($_SESSION['resultado']);
        }
        ?>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->