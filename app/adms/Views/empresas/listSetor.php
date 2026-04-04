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
            <span class="title-content">Listar Setores da Empresas</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['add_setor']) {
                    echo "<a href='" . URLADM . "add-setor/index' class='btn-success'>Cadastrar</a>";
                }                
                ?>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_nome = "";
                    if (isset($valorForm['search_nome'])) {
                        $search_nome = $valorForm['search_nome'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Setor: </label>
                        <input type="text" name="search_nome" id="search_nome" class="input-search" placeholder="Pesquisar pelo setor..." value="<?php echo $search_nome; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchSetor" class="btn-info" value="Pesquisar">Pesquisar</button>
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
                    <th class="list-head-content">Empresa</th>
                    <th class="list-head-content">Ação</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listsetor'] as $setor) {
                    extract($setor);
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id; ?></td>
                        <td class="list-body-content"><?php echo $name; ?></td>
                        <td class="list-body-content"><?php echo $nome_fantasia_emp; ?></td>
                        
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php                                    
                                    if ($this->data['button']['view_setor']) {
                                        echo "<a href='" . URLADM . "view-setor/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_setor']) {
                                        echo "<a href='" . URLADM . "edit-setor/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_setor']) {
                                        echo "<a href='" . URLADM . "delete-setor/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este Setor da empresa?\")'>Apagar</a>";
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