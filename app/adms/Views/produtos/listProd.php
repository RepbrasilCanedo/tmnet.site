<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
$valorForm = $this->data['form'] ?? [];
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Equipamentos e Serviços</span>
            <div class="top-list-right">
                <?php if ($this->data['button']['add_prod']) echo "<a href='".URLADM."add-prod/index' class='btn-success'>Cadastrar</a>"; ?>
            </div>
        </div>

        <div class="row-input" style="margin-bottom: 20px;">
            <div class="column"><div class="alert alert-info" style="padding:10px;"><i class="fa-solid fa-magnifying-glass"></i> Encontrados: <strong><?= $this->data['total_geral'] ?></strong></div></div>
            <?php if ($this->data['total_vencidos'] > 0): ?>
                <div class="column"><div class="alert alert-danger" style="padding:10px;"><i class="fa-solid fa-circle-exclamation"></i> Vencidos: <strong><?= $this->data['total_vencidos'] ?></strong></div></div>
            <?php endif; ?>
            <?php if ($this->data['total_avencer'] > 0): ?>
                <div class="column"><div class="alert alert-warning" style="padding:10px; color:#856404; background:#ffda85;"><i class="fa-solid fa-clock"></i> A Vencer (30d): <strong><?= $this->data['total_avencer'] ?></strong></div></div>
            <?php endif; ?>
        </div>

        <div class="top-list">
            <form method="POST" action="">
                <div class="row-input">
                    <div class="column"><label class="title-input">Tipo:</label>
                        <select name="search_tipo" class="input-adm"><option value="">Todos</option>
                            <?php foreach($this->data['select']['tipo_equip'] as $t) echo "<option value='{$t['id']}' ".((isset($valorForm['search_tipo']) && $valorForm['search_tipo']==$t['id'])?'selected':'').">{$t['name']}</option>"; ?>
                        </select></div>
                    <div class="column"><label class="title-input">Cliente:</label>
                        <select name="search_emp" class="input-adm"><option value="">Todos</option>
                            <?php foreach($this->data['select']['nome_clie'] as $c) echo "<option value='{$c['id']}' ".((isset($valorForm['search_emp']) && $valorForm['search_emp']==$c['id'])?'selected':'').">{$c['nome_fantasia']}</option>"; ?>
                        </select></div>
                    <div class="column"><label class="title-input">Situação:</label>
                        <select name="search_sit" class="input-adm"><option value="">Todos</option>
                            <?php foreach($this->data['select']['sit_equip'] as $s) echo "<option value='{$s['id']}' ".((isset($valorForm['search_sit']) && $valorForm['search_sit']==$s['id'])?'selected':'').">{$s['name']}</option>"; ?>
                        </select></div>
                </div>
                <div class="row-input mt-2">
                    <div class="column"><label class="title-input">Nome:</label><input type="text" name="search_prod" class="input-adm" value="<?= $valorForm['search_prod'] ?? '' ?>"></div>
                    <div class="column"><label class="title-input">Vencimento Início:</label><input type="date" name="date_start" class="input-adm" value="<?= $valorForm['date_start'] ?? '' ?>"></div>
                    <div class="column"><label class="title-input">Fim:</label><input type="date" name="date_end" class="input-adm" value="<?= $valorForm['date_end'] ?? '' ?>"></div>
                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchProdEmp" class="btn-info" value="Pesquisar">Filtrar</button>
                        <button type="submit" name="SendExportProd" class="btn-success" value="Exportar"><i class="fa-solid fa-file-excel"></i> Excel</button>
                        <a href="<?= URLADM ?>list-prod/index" class="btn-warning">Limpar</a>
                    </div>
                </div>
            </form>
        </div>

        <table class="table table-hover table-list">
            <thead class="list-head">
                <tr>
                    <th class="table-sm-none">ID</th>
                    <th>Equipamento</th>
                    <th>Contrato (Ativo)</th> <th class="table-sm-none">Cliente</th>
                    <th class="table-sm-none">Vencimento</th>
                    <th>Situação</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php foreach ($this->data['listProd'] as $prod) {
                    extract($prod);
                    
                    // Tratamento de Vencimento Dinâmico
                    $dv = (!empty($final_contr) && $final_contr != '0000-00-00') ? strtotime($final_contr) : null;
                    $hj = strtotime(date('Y-m-d')); 
                    $p30 = strtotime('+30 days');
                    
                    $style = ""; $icon = "";
                    if ($dv !== null) {
                        $style = ($dv < $hj) ? "style='color:#d9534f; font-weight:bold;'" : (($dv <= $p30) ? "style='color:#ffbc23; font-weight:bold;'" : "");
                        $icon = ($dv < $hj) ? "<i class='fa-solid fa-circle-exclamation'></i>" : (($dv <= $p30) ? "<i class='fa-solid fa-hourglass-half'></i>" : "");
                        $vencimentoExibicao = date('d/m/Y', $dv);
                    } else {
                        $vencimentoExibicao = "Indeterminado";
                    }
                ?>
                    <tr>
                        <td class="table-sm-none"><?= $id ?></td>
                        <td><?= $name ?> <?= $icon ?></td>
                        <td><?= $name_contr ?? '<span style="color:#999;">Sem contrato</span>' ?></td> 
                        <td class="table-sm-none"><?= $nome_fantasia_clie ?></td>
                        <td class="table-sm-none" <?= $style ?>><?= $vencimentoExibicao ?></td>
                        <td><?= $name_sit ?></td>
                        <td class="text-center">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?= $id ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?= $id ?>" class="dropdown-action-item">
                                    <?php 
                                    if ($this->data['button']['view_prod']) echo "<a href='".URLADM."view-prod/index/$id'>Visualizar</a>";
                                    if ($this->data['button']['edit_prod']) echo "<a href='".URLADM."edit-prod/index/$id'>Editar</a>";
                                    if ($this->data['button']['delete_prod']) echo "<a href='".URLADM."delete-prod/index/$id' class='text-danger' onclick='return confirm(\"Excluir?\")'>Apagar</a>"; 
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