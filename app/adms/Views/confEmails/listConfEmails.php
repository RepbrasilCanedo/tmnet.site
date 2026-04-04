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
            <span class="title-content">Listar E-mail</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_conf_emails']) {
                    echo "<a href='" . URLADM . "add-conf-emails/index' class='btn-success'>Cadastrar</a>";
                }
                ?>
            </div>
        </div>
        
        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_title = "";
                    if (isset($valorForm['search_title'])) {
                        $search_title = $valorForm['search_title'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Titulo: </label>
                        <input type="text" name="search_title" id="search_title" class="input-search" placeholder="Pesquisar pelo titulo..." value="<?php echo $search_title; ?>">
                    </div>

                    <?php
                    $search_email = "";
                    if (isset($valorForm['search_email'])) {
                        $search_email = $valorForm['search_email'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">E-mail: </label>
                        <input type="text" name="search_email" id="search_email" class="input-search" placeholder="Pesquisar pelo e-mail..." value="<?php echo $search_email; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchConfemail" class="btn-info" value="Pesquisar">Pesquisar</button>
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
        <table class="table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Título</th>
                    <th class="list-head-content table-sm-none">Nome</th>
                    <th class="list-head-content table-md-none">E-mail</th>
                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listConfEmails'] as $confEmail) {
                    extract($confEmail);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $title; ?></td>
                        <td class="list-body-content table-sm-none"><?php echo $name; ?></td>
                        <td class="list-body-content table-md-none"><?php echo $email; ?></td>
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_conf_emails']) {
                                        echo "<a href='" . URLADM . "view-conf-emails/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_conf_emails']) {
                                        echo "<a href='" . URLADM . "edit-conf-emails/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_conf_emails']) {
                                        echo "<a href='" . URLADM . "delete-conf-emails/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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