<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

$valorForm = $this->data['form'] ?? [];
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Listar Clientes</span>
            <div class="top-list-right">
                <?php if ($this->data['button']['add_empresas']) echo "<a href='".URLADM."add-empresas/index' class='btn-success'>Cadastrar</a>"; ?>
            </div>
        </div>

        <div class="row-input" style="margin-bottom: 20px;">
            <div class="column">
                <div class="alert alert-info" style="padding:10px;">
                    <i class="fa-solid fa-building"></i> Clientes Cadastrados: <strong><?= $_SESSION['resultado'] ?? 0 ?></strong>
                    <?php unset($_SESSION['resultado']); ?>
                </div>
            </div>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input">
                    <div class="column">
                        <label class="title-input">CNPJ/CPF:</label>
                        <input type="text" name="search_cnpj" class="input-adm" placeholder="Pesquisar documento..." value="<?= $valorForm['search_cnpj'] ?? '' ?>">
                    </div>

                    <div class="column">
                        <label class="title-input">Razão Social:</label>
                        <input type="text" name="search_razao" class="input-adm" placeholder="Pesquisar razão..." value="<?= $valorForm['search_razao'] ?? '' ?>">
                    </div>

                    <div class="column">
                        <label class="title-input">Nome Fantasia:</label>
                        <input type="text" name="search_fantasia" class="input-adm" placeholder="Pesquisar fantasia..." value="<?= $valorForm['search_fantasia'] ?? '' ?>">
                    </div>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchEmpresa" class="btn-info" value="Pesquisar">Filtrar</button>
                        <a href="<?= URLADM ?>list-empresas/index" class="btn-warning">Limpar</a>
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
                    <th class="table-sm-none">ID</th>
                    <th>Nome Fantasia</th>
                    <th class="table-sm-none">Documento</th>
                    <th class="table-sm-none">Cidade/UF</th>
                    <th class="table-sm-none">Situação</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php foreach ($this->data['listEmpresas'] as $empresas) { 
                    extract($empresas); 
                ?>
                    <tr>
                        <td class="table-sm-none"><?= $id ?></td>
                        <td>
                            <span class="fw-bold"><?= $nome_fantasia ?></span><br>
                            <small class="text-muted table-sm-block" style="display:none;"><?= $razao_social ?></small>
                        </td>
                        <td class="table-sm-none"><?= $cnpjcpf ?></td>
                        <td class="table-sm-none"><?= $cidade ?>/<?= $uf ?? '' ?></td>
                        <td class="table-sm-none"><?= $name_sit ?></td>
                        
                        <td class="text-center">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?= $id ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?= $id ?>" class="dropdown-action-item">
                                    <?php                                     
                                    if ($this->data['button']['view_empresas']) echo "<a href='".URLADM."view-empresas/index/$id'>Visualizar</a>";
                                    if ($this->data['button']['edit_empresas']) echo "<a href='".URLADM."edit-empresas/index/$id'>Editar</a>";
                                    if ($this->data['button']['delete_empresas']) echo "<a href='".URLADM."delete-empresas/index/$id' onclick='return confirm(\"Excluir este cliente?\")' class='text-danger'>Apagar</a>";
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?= $this->data['pagination'] ?>
    </div>
</div>