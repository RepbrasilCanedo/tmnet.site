<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>

<style>
    /* DOCAN FIX: Estilos Responsivos para Mobile */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; margin-bottom: 15px; border-radius: 8px; }
    .row-input-search { display: flex; flex-wrap: wrap; gap: 10px; align-items: flex-end; width: 100%; }
    .row-input-search .column { flex: 1; min-width: 200px; margin: 0 !important; }
    .row-input-search .margin-top-search { flex: 0 0 auto; margin-bottom: 2px !important; }
    @media (max-width: 768px) {
        .table-list th, .table-list td { font-size: 12px; padding: 8px 5px; }
        .row-input-search .margin-top-search { width: 100%; }
        .row-input-search .margin-top-search button { width: 100%; }
        .top-list { flex-direction: column; align-items: flex-start; gap: 10px; }
        .top-list-right { width: 100%; }
        .top-list-right .btn-success { display: block; text-align: center; }
    }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Usuário(as)</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_users']) {
                    echo "<a href='" . URLADM . "add-users/index' class='btn-success'>Cadastrar</a>";
                }
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="" style="width: 100%;">
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

                    <?php if (($_SESSION['adms_access_level_id'] <= 2)) {?>
                        <?php
                        $search_empresa = "";
                        if (isset($valorForm['search_empresa'])) {
                            $search_empresa = $valorForm['search_empresa'];
                        }
                        ?>
                        <div class="column">
                            <label class="title-input-search">Empresa: </label>
                            <input type="text" name="search_empresa" id="search_empresa" class="input-search" placeholder="Pesquisar pela empresa..." value="<?php echo $search_empresa; ?>">
                        </div>
                    <?php }else {?>
                        <div class="column" style="display:none;">
                            <label class="title-input-search">Empresa: </label>
                            <input type="text" name="search_empresa" id="search_empresa" class="input-search" placeholder="Pesquisar pela empresa..." value="<?php echo $search_empresa ?? ''; ?>">
                        </div>
                    <?php } ?>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchUser" class="btn-info" value="Pesquisar">Pesquisar</button>
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
        
        <?php 
        if (isset($_SESSION['resultado'])) {
            echo "<div style='margin-bottom: 10px; font-weight: bold; color: #555;'>Total de Usuários Cadastrados:  " . $_SESSION['resultado'] . "</div>";
        }
        ?>

        <div class="table-responsive">
            <table class="table table-hover table-list">
                <thead class="list-head">
                    <tr>
                        <th class="list-head-content">ID</th>
                        <th class="list-head-content table-sm-none">Nome</th>
                        <th class="list-head-content">Apelido</th>
                        <th class="list-head-content">Pontuação</th>
                        <th class="list-head-content">Estilo</th>
                        <th class="list-head-content table-sm-none">Mão Dominante</th>
                        <th class="list-head-content">Usuario</th>
                        <th class="list-head-content table-md-none">Tel/WhatsApp</th>
                        <th class="list-head-content table-md-none">Situação</th>
                        <th class="list-head-content">Ações</th>
                    </tr>
                </thead>
                <tbody class="list-body">
                    <?php
                    foreach ($this->data['listUsers'] as $user) {
                        extract($user);
                    ?>
                        <tr>
                            <td class="list-body-content"><?php echo $id; ?></td>
                            <td class="list-body-content table-sm-none"><?php echo $name_usr; ?></td>
                            <td class="list-body-content"><?php echo $apelido; ?></td>
                            <td class="list-body-content"><?php echo $pontuacao_ranking; ?></td>
                            <td class="list-body-content"><?php echo $estilo_jogo; ?></td>
                            <td class="list-body-content table-sm-none"><?php echo $mao_dominante; ?></td>
                            <td class="list-body-content"><?php echo $name_lev; ?></td>
                            <td class="list-body-content table-sm-none"><?php echo $telefone; ?></td>
                            <td class="list-body-content table-md-none">
                                <?php echo "<span style='color: $color'>$name_sit</span>"; ?>
                            </td>
                            <td class="list-body-content">
                                <div class="dropdown-action">
                                    <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                    <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                        <?php
                                        if ($this->data['button']['view_users']) {
                                            echo "<a href='" . URLADM . "view-users/index/$id'>Visualizar</a>";
                                        }
                                        if ($this->data['button']['edit_users']) {
                                            echo "<a href='" . URLADM . "edit-users/index/$id'>Editar</a>";
                                        }
                                        if ($this->data['button']['delete_users']) {
                                            echo "<a href='" . URLADM . "delete-users/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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
        </div>

        <?php echo $this->data['pagination']; ?>
        <?php 
        if (isset($_SESSION['resultado'])) {
            unset($_SESSION['resultado']);
        }
        ?>
    </div>
</div>