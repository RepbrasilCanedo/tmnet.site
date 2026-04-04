<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
//var_dump($this->data['listEmpresas']);
?>

<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Empresas</span>
            <div class="top-list-right">
                <?php
                if (($_SESSION['adms_access_level_id'] == 1) or ($_SESSION['adms_access_level_id'] == 2)) {
                    if ($this->data['button']['add_emp_principal']) {
                        echo "<a href='" . URLADM . "add-emp-principal/index' class='btn-success'>Cadastrar</a>";
                    } else if ($this->data['button']['edit_profile_logo']) {
                        echo "<a href='" . URLADM . "edit-profile-logo/index' class='btn-warning'>Inserir Logo</a>";
                    }  
                }             
                ?>
            </div>
        </div>
        <?php if (($_SESSION['adms_access_level_id'] == 1) or ($_SESSION['adms_access_level_id'] == 2)) {?>
            
        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input-search">
                    <?php
                    $search_cnpj = "";
                    if (isset($valorForm['search_cnpj'])) {
                        $search_cnpj = $valorForm['search_cnpj'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Cnpj: </label>
                        <input type="text" name="search_cnpj" id="search_cnpj" class="input-search" placeholder="Pesquisar pelo CNPJ" value="<?php echo $search_cnpj; ?>">
                    </div>

                    <?php
                    $search_razao = "";
                    if (isset($valorForm['search_razao'])) {
                        $search_razao = $valorForm['search_razao'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Razão Social: </label>
                        <input type="text" name="search_razao" id="search_razao" class="input-search" placeholder="Pesquisar pela razao social" value="<?php echo $search_razao; ?>">
                    </div>

                    <?php
                    $search_fantasia = "";
                    if (isset($valorForm['search_fantasia'])) {
                        $search_fantasia = $valorForm['search_fantasia'];
                    }
                    ?>
                    <div class="column">
                        <label class="title-input-search">Nome Fantasia: </label>
                        <input type="text" name="search_fantasia" id="search_fantasia" class="input-search" placeholder="Pesquisar pelo nome fantasia..." value="<?php echo $search_fantasia; ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchEmpPrincipal" class="btn-info" value="Pesquisar">Pesquisar</button>
                    </div>
                </div>
            </form>

        </div>

        <?php } ?>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <?php 
        if (($_SESSION['adms_access_level_id'] == 1) or ($_SESSION['adms_access_level_id'] == 2)) {
            echo $this->data['pagination']; 
            if (isset($_SESSION['resultado'])) {
                echo "Total de Empresas Cadastradas:  " . $_SESSION['resultado'];
            }
        } ?>

        
        <table class="table table-hover table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content table-sm-none">ID</th>
                    <th class="list-head-content table-sm-none">Razão Social</th>
                    <th class="list-head-content">Nome de Fantasia</th>
                    <th class="list-head-content table-sm-none">Cnpj</th>
                    <th class="list-head-content table-sm-none">Bairro</th>
                    <th class="list-head-content ">Cidade</th>
                    <th class="list-head-content table-sm-none">Situação</th>

                    <th class="list-head-content">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                foreach ($this->data['listEmpPrincipal'] as $listEmpPrincipal) {
                    extract($listEmpPrincipal);
                ?>
                    <tr>
                        <td class="list-body-content table-sm-none"><?php echo $id; ?></td>
                        <td class="list-body-content  table-sm-none"><?php echo $razao_social; ?></td>
                        <td class="list-body-content  table-sm-none"><?php echo $nome_fantasia; ?></td>
                        <td class="list-body-content table-sm-none"><?php echo $cnpj; ?></td>
                        <td class="list-body-content table-sm-none"><?php echo $bairro; ?></td>
                        <td class="list-body-content"><?php echo $cidade; ?></td>
                        <td class="list-body-content table-sm-none"><?php echo $name_sit; ?></td>
                        
                        <td class="list-body-content">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id; ?>" class="dropdown-action-item">
                                    <?php                                    
                                    if ($this->data['button']['view_emp_principal']) {
                                        echo "<a href='" . URLADM . "view-emp-principal/index/$id'>Visualizar</a>";
                                    }
                                    if ($this->data['button']['edit_emp_principal']) {
                                        echo "<a href='" . URLADM . "edit-emp-principal/index/$id'>Editar</a>";
                                    }
                                    if ($this->data['button']['delete_emp_principal']) {
                                        echo "<a href='" . URLADM . "delete-emp-principal/index/$id' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")'>Apagar</a>";
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

        
        <?php 
        if (($_SESSION['adms_access_level_id'] == 1) or ($_SESSION['adms_access_level_id'] == 2)) {
            echo $this->data['pagination']; 
            if (isset($_SESSION['resultado'])) {
                echo "Total de Empresas Cadastradas:  " . $_SESSION['resultado'];
            }
        } ?>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->