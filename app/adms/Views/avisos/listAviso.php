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
            <span class="title-content">Listar Aviso</span>
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
                    <th class="list-head-content">Titulo Aviso</th>
                    <th class="list-head-content">Texto</th>
                    <th class="list-head-content">Titulo Alerta</th>
                    <th class="list-head-content">Alerta</th>
                    <th class="list-head-content table-sm-none">Ult. Alteração</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listAviso'] as $listAviso) {
                    extract($listAviso);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        <td class="list-body-content"><?php echo $texto; ?></td>
                        <td class="list-body-content"><?php echo $tit_aviso; ?></td>
                        <td class="list-body-content"><?php echo $aviso; ?></td>                        
                        <td class="list-body-content table-sm-none"><?php echo date('d/m/Y H:i:s', strtotime($modified)); ?></td>

                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['edit_aviso']) {
                                        echo "<a href='" . URLADM . "edit-aviso/index/$id'>Editar</a>";
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