<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos do Dashboard do Clube */
    .emp-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
    .emp-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #0044cc; }
    .emp-card h3 { margin-top: 0; color: #333; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
    
    .emp-info-line { margin-bottom: 10px; font-size: 14px; }
    .emp-info-line strong { color: #555; }
    
    .torneio-list { list-style: none; padding: 0; margin: 0; }
    .torneio-item { display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f8f9fa; border-left: 4px solid #28a745; margin-bottom: 10px; border-radius: 4px; }
    .torneio-item.historico { border-left-color: #6c757d; background: #fdfdfd; }
    .torneio-item:hover { background: #eef2fa; }
    
    .t-dados { display: flex; flex-direction: column; }
    .t-nome { font-weight: bold; color: #0044cc; font-size: 15px; }
    .t-data { font-size: 12px; color: #666; }
    .t-inscritos { font-size: 12px; background: #e0f2f1; color: #0c5460; padding: 3px 8px; border-radius: 12px; display: inline-block; margin-top: 5px; font-weight: bold; width: fit-content; }
    
    .btn-acao-sm { padding: 6px 12px; font-size: 12px; border-radius: 4px; text-decoration: none; font-weight: bold; display: inline-block; cursor: pointer; border: none; }
    .btn-sumula { background: #ffc107; color: #333; } .btn-sumula:hover { background: #e0a800; }
    .btn-print { background: #17a2b8; color: #fff; } .btn-print:hover { background: #138496; }

    /* ============================================================== */
    /* DOCAN FIX: RESPONSIVIDADE PARA TELEMÓVEIS (Mobile First)       */
    /* ============================================================== */
    @media (max-width: 768px) {
        .emp-grid { 
            grid-template-columns: 1fr; /* Empilha as colunas uma por cima da outra */
        }
        .top-list { 
            flex-direction: column; 
            gap: 15px; 
            align-items: flex-start;
        }
        .top-list-right {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
        }
        .top-list-right a {
            flex: 1; /* Faz os botões de topo ocuparem o espaço todo */
            text-align: center;
        }
        .torneio-item { 
            flex-direction: column; 
            align-items: flex-start; 
            gap: 15px; 
        }
        .torneio-item > div:last-child {
            display: flex;
            width: 100%;
            gap: 10px;
        }
        .btn-acao-sm {
            flex: 1; /* Faz os botões da súmula crescerem e dividirem o espaço */
            text-align: center;
            margin-right: 0 !important;
        }
    }

    /* Mágica de Impressão */
    .print-only { display: none; }
    @media print {
        body * { visibility: hidden; }
        .print-only, .print-only * { visibility: visible; }
        .print-only { display: block; position: absolute; left: 0; top: 0; width: 100%; padding: 20px; }
        .dash-wrapper { display: none; }
    }
    .table-print { width: 100%; border-collapse: collapse; margin-top: 20px; font-family: Arial, sans-serif; font-size: 12px;}
    .table-print th, .table-print td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table-print th { background-color: #f2f2f2; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Painel do Clube / Liga</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_emp_principal']) {
                    echo "<a href='" . URLADM . "list-emp-principal/index' class='btn-info'>Listar Clubes</a> ";
                }
                if ($this->data['button']['edit_emp_principal']) {
                    echo "<a href='" . URLADM . "edit-emp-principal/index/". $this->data['viewEmpPrincipal'][0]['id'] ."'class='btn-warning'>Editar Dados</a>";
                }
                if ($this->data['button']['edit_profile_logo']) {
                    echo "<a href='" . URLADM . "edit-profile-logo/index/". $this->data['viewEmpPrincipal'][0]['id'] ."' class='btn-success'>Editar Logo</a>";
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

        <?php if (!empty($this->data['viewEmpPrincipal'])) { 
            extract($this->data['viewEmpPrincipal'][0]); 
            $compAtivas = $this->data['viewEmpPrincipal']['comp_ativas'] ?? [];
            $compHist = $this->data['viewEmpPrincipal']['comp_historico'] ?? [];
            $atletasFiliados = $this->data['viewEmpPrincipal']['atletas_filiados'] ?? [];
        ?>
            
        <div class="emp-grid">
            <div>
                <div class="emp-card" style="text-align: center; border-top-color: #333;">
                    <?php
                    if ((!empty($logo_emp)) && (file_exists("app/adms/assets/image/logo/clientes/$id/$logo_emp"))) {
                        echo "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/$id/$logo_emp' style='max-width:150px; border-radius:8px; margin-bottom:15px;'>";
                    } else {
                        echo "<div style='background:#f4f4f4; width:100px; height:100px; border-radius:50%; line-height:100px; margin:0 auto 15px auto; font-size:30px; color:#ccc;'><i class='fa-solid fa-shield'></i></div>";
                    }
                    ?>
                    <h2 style="margin:0; font-size: 18px; color: #0044cc;"><?= $nome_fantasia; ?></h2>
                    <p style="margin:5px 0; font-size: 13px; color: #666;"><?= $razao_social; ?> (ID: <?= $id ?>)</p>
                    <span style="font-size: 12px; background: #e0f2f1; color: #0c5460; padding: 4px 10px; border-radius: 12px; font-weight: bold;"><?= $name_sit; ?></span>
                </div>

                <div class="emp-card" style="margin-top: 20px;">
                    <h3><i class="fa-solid fa-address-card"></i> Informações de Contato</h3>
                    <div class="emp-info-line"><strong>CNPJ:</strong> <?= $cnpj; ?></div>
                    <div class="emp-info-line"><strong>Responsável:</strong> <?= $contato; ?></div>
                    <div class="emp-info-line"><strong>E-mail:</strong> <?= $email; ?></div>
                    <div class="emp-info-line"><strong>Telefone:</strong> <?= $telefone; ?></div>
                    <div class="emp-info-line"><strong>Endereço:</strong> <?= $logradouro; ?>, <?= $bairro; ?> - <?= $cidade; ?>/<?= $uf; ?></div>
                </div>

                <div class="emp-card" style="margin-top: 20px; border-top-color: #17a2b8;">
                    <h3><i class="fa-solid fa-chart-pie"></i> Relatórios do Clube</h3>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span style="font-size: 14px; color: #555;">Total de Atletas Filiados:</span>
                        <span style="font-size: 18px; font-weight: bold; color: #17a2b8;"><?= count($atletasFiliados) ?></span>
                    </div>
                    <button onclick="window.print()" class="btn-acao-sm btn-print" style="width: 100%; text-align: center; padding: 10px;"><i class="fa-solid fa-print"></i> Imprimir Lista de Atletas</button>
                </div>
            </div>

            <div>
                <div class="emp-card" style="border-top-color: #28a745;">
                    <h3><i class="fa-solid fa-trophy"></i> Competições em Andamento / Futuras</h3>
                    <?php if (!empty($compAtivas)): ?>
                        <ul class="torneio-list">
                            <?php foreach($compAtivas as $ca): ?>
                                <li class="torneio-item">
                                    <div class="t-dados">
                                        <span class="t-nome"><?= $ca['nome_torneio'] ?></span>
                                        <span class="t-data"><i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($ca['data_evento'])) ?></span>
                                        <span class="t-inscritos"><i class="fa-solid fa-users"></i> <?= $ca['total_inscritos'] ?> atletas Aprovados</span>
                                    </div>
                                    <div>
                                        <a href="<?= URLADM ?>view-competicao/index/<?= $ca['id'] ?>" class="btn-acao-sm" style="background: #0044cc; color: white; margin-right: 5px;">Painel</a>
                                        <a href="<?= URLADM ?>imprimir-sumula/index/<?= $ca['id'] ?>" target="_blank" class="btn-acao-sm btn-sumula"><i class="fa-solid fa-file-pdf"></i> Súmula</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="font-size: 13px; color: #888; text-align: center; padding: 20px; border: 1px dashed #ddd;">Nenhuma competição em andamento.</p>
                    <?php endif; ?>
                </div>

                <div class="emp-card" style="margin-top: 20px; border-top-color: #6c757d;">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Histórico de Competições</h3>
                    <?php if (!empty($compHist)): ?>
                        <ul class="torneio-list">
                            <?php foreach($compHist as $ch): ?>
                                <li class="torneio-item historico">
                                    <div class="t-dados">
                                        <span class="t-nome" style="color: #555;"><?= $ch['nome_torneio'] ?></span>
                                        <span class="t-data"><?= date('d/m/Y', strtotime($ch['data_evento'])) ?></span>
                                        <span class="t-inscritos" style="background:#eee; color:#666;"><?= $ch['total_inscritos'] ?> inscritos</span>
                                    </div>
                                    <div>
                                        <a href="<?= URLADM ?>view-competicao/index/<?= $ch['id'] ?>" class="btn-acao-sm" style="background: #6c757d; color: white; width: 100%; text-align: center;">Ver Detalhes</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="font-size: 13px; color: #888; text-align: center; padding: 20px; border: 1px dashed #ddd;">Nenhum histórico encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php } ?>
    </div>
</div>

<div class="print-only">
    <?php if (!empty($this->data['viewEmpPrincipal'])): ?>
    <h2 style="text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px;">Relatório de Atletas Filiados</h2>
    <h4 style="text-align: center; margin-top: 5px;">Clube: <?= $nome_fantasia; ?> | Data: <?= date('d/m/Y') ?></h4>
    
    <table class="table-print">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 30%;">Atleta (Apelido)</th>
                <th style="width: 25%;">Contato</th>
                <th style="width: 25%;">Localidade</th>
                <th style="width: 15%; text-align: center;">Pontuação</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $count = 1;
            foreach($atletasFiliados as $atl): ?>
                <tr>
                    <td><?= $count++ ?></td>
                    <td><strong><?= $atl['name'] ?></strong><br><small>(<?= $atl['apelido'] ?>)</small></td>
                    <td><?= $atl['telefone'] ?><br><small><?= $atl['email'] ?></small></td>
                    <td><?= !empty($atl['cidade']) ? $atl['cidade'].'/'.$atl['estado'] : 'Não informado' ?></td>
                    <td style="text-align: center;"><?= $atl['pontuacao_ranking'] ?> pts</td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($atletasFiliados)): ?>
                <tr><td colspan="5" style="text-align: center;">Nenhum atleta filiado encontrado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>