<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .grid-torneios { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 15px; }
    .card-torneio { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.08); border-top: 5px solid #0044cc; display: flex; flex-direction: column; transition: transform 0.2s; }
    .card-torneio:hover { transform: translateY(-5px); }
    .vitrine-header { padding: 15px; background: #f8f9fa; border-bottom: 1px solid #eee; position: relative; }
    .vitrine-body { padding: 20px 15px; flex-grow: 1; }
    .vitrine-body p { margin: 8px 0; font-size: 14px; color: #444; }
    .vitrine-body i { color: #0044cc; width: 20px; text-align: center; margin-right: 5px; }
    .card-footer { padding: 15px; background-color: #fafafa; border-top: 1px solid #eee; }
    .btn-inscrever { display: block; width: 100%; background: #28a745; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.3s; font-size: 14px; margin-bottom: 5px; }
    .btn-inscrever:hover { background: #218838; }
    .btn-atualizar { display: block; width: 100%; background: #17a2b8; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.3s; font-size: 14px; margin-bottom: 5px; }
    .btn-cancelar { display: block; width: 100%; background: #dc3545; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; text-transform: uppercase; cursor: pointer; transition: 0.3s; font-size: 14px; }
    .box-divisao { border: 1px solid #cce5ff; background: #eef2fa; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
    .box-divisao label { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 14px; font-weight: bold; color: #0044cc; background: #fff; padding: 10px; border-radius: 4px; border: 1px solid #ddd; transition: 0.2s; }
    .box-divisao label:hover { background: #f1f1f1; }
    .box-pix { background: #e8f5e9; border: 1px solid #c3e6cb; padding: 12px; border-radius: 6px; margin-bottom: 15px; text-align: center; }
    .box-pix p { margin: 5px 0; font-size: 13px; color: #155724; }
    .box-pix .chave { font-size: 15px; font-weight: bold; background: #fff; padding: 5px 10px; border-radius: 4px; border: 1px dashed #28a745; display: inline-block; margin-top: 5px; }
    .total-pagar { font-size: 18px; font-weight: bold; color: #28a745; margin-top: 10px; }
    
    .status-badge { position: absolute; top: 10px; right: 10px; padding: 4px 8px; border-radius: 12px; font-size: 10px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.2); display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .badge-ok { background-color: #28a745; color: white; padding: 2px 8px; border-radius: 12px; }
    .badge-warn { background-color: #ffc107; color: #333; padding: 2px 8px; border-radius: 12px; }
    .badge-info { background-color: #17a2b8; color: white; padding: 2px 8px; border-radius: 12px; }
    
    /* Estilo dos Radio Buttons de Tipo de Inscrição */
    .tipo-insc-box { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 15px; justify-content: center; }
    .tipo-insc-box label { background: #fff; border: 1px solid #ccc; padding: 8px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; cursor: pointer; transition: 0.2s; color: #555; }
    .tipo-insc-box input[type="radio"] { display: none; }
    .tipo-insc-box input[type="radio"]:checked + span { color: #0044cc; }
    .tipo-insc-box label:has(input[type="radio"]:checked) { border-color: #0044cc; background: #eef2fa; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div>
                <span class="title-content" style="margin: 0; display: block;">🏆 Inscrições Abertas</span>
                <p style="color: #666; margin: 0; font-size: 14px;">Selecione o torneio e as categorias que deseja disputar.</p>
            </div>
            
            <div class="top-list-right" style="margin: 0;">
                <a href="<?= URLADM ?>perfil-atleta/index" class="btn-info" style="margin: 0;"><i class="fa-solid fa-user"></i> Meu Passaporte</a>
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

        <?php if (!empty($this->data['torneios'])): ?>
            <div class="grid-torneios">
                <?php foreach ($this->data['torneios'] as $torneio): 
                    $jaInscrito = !empty($torneio['categorias_inscritas']);
                    $arrayInscritas = $jaInscrito ? explode(',', $torneio['categorias_inscritas']) : [];
                    $statusPag = $torneio['status_pagamento'] ?? 1;
                    $tipoSalvo = $torneio['tipo_inscricao_salvo'] ?? 'Geral';
                    
                    $logoClube = (!empty($torneio['clube_logo']) && file_exists("app/adms/assets/image/logo/clientes/" . $torneio['clube_id'] . "/" . $torneio['clube_logo']))
                        ? URLADM . "app/adms/assets/image/logo/clientes/" . $torneio['clube_id'] . "/" . $torneio['clube_logo']
                        : URLADM . "app/adms/assets/image/logo/clientes/logo_padrao.png";

                    $valUmaGeral = (float)($torneio['valor_uma_categoria'] ?? 0);
                    $valDuasGeral = (float)($torneio['valor_duas_categorias'] ?? 0);
                    $valUmaSocio = (float)($torneio['valor_uma_socio'] ?? 0);
                    $valDuasSocio = (float)($torneio['valor_duas_socio'] ?? 0);
                    $valUmaEst = (float)($torneio['valor_uma_estudante'] ?? 0);
                    $valDuasEst = (float)($torneio['valor_duas_estudante'] ?? 0);
                    
                    $valorInicial = 0;
                    if(count($arrayInscritas) == 1) $valorInicial = $valUmaGeral;
                    if(count($arrayInscritas) == 2) $valorInicial = $valDuasGeral;
                ?>
                    <div class="card-torneio" style="<?= $jaInscrito ? 'border-top-color: #28a745;' : '' ?>">
                        <div class="vitrine-header" style="display: flex; align-items: center; gap: 15px;">
                            
                            <?php if ($jaInscrito): ?>
                                <div class="status-badge">
                                    <span class="badge-ok"><i class="fa-solid fa-check"></i> INSCRITO</span>
                                    <?php if($statusPag == 1): ?>
                                        <span class="badge-warn"><i class="fa-solid fa-hourglass-half"></i> Aguardando Pgto</span>
                                    <?php elseif($statusPag == 2): ?>
                                        <span class="badge-ok"><i class="fa-solid fa-check-double"></i> Pago</span>
                                    <?php elseif($statusPag == 3): ?>
                                        <span class="badge-info"><i class="fa-solid fa-star"></i> Isento</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div style="width: 55px; height: 55px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd; overflow: hidden; flex-shrink: 0;">
                                <img src="<?= $logoClube ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>

                            <div>
                                <h3 style="margin: 0; color: #333; font-size: 18px; line-height: 1.2; width: 75%;"><?= $torneio['nome_torneio'] ?></h3>
                                <small style="color: #0044cc; font-weight: bold;"><?= $torneio['clube_nome'] ?></small>
                            </div>
                        </div>
                        
                        <div class="vitrine-body">
                            <p><i class="fa-regular fa-calendar"></i> <strong>Data:</strong> <?= date('d/m/Y', strtotime($torneio['data_evento'])) ?></p>
                            <p><i class="fa-solid fa-location-dot"></i> <strong>Local:</strong> <?= $torneio['local_evento'] ?></p>
                            <p><i class="fa-solid fa-table-tennis-paddle-ball"></i> <strong>Categoria Base:</strong> <?= $torneio['categoria_cbtm'] ?></p>
                        </div>
                        
                        <div class="card-footer">
                            <form method="POST" action="" 
                                  data-uma-geral="<?= $valUmaGeral ?>" data-duas-geral="<?= $valDuasGeral ?>"
                                  data-uma-socio="<?= $valUmaSocio ?>" data-duas-socio="<?= $valDuasSocio ?>"
                                  data-uma-est="<?= $valUmaEst ?>" data-duas-est="<?= $valDuasEst ?>">
                                
                                <input type="hidden" name="competicao_id" value="<?= $torneio['id'] ?>">
                                
                                <?php if (!empty($torneio['categorias_elegiveis'])): ?>
                                    <div class="tipo-insc-box">
                                        <label>
                                            <input type="radio" name="tipo_inscricao" value="Geral" <?= ($tipoSalvo == 'Geral') ? 'checked' : '' ?> onchange="atualizarValor(this)">
                                            <span><i class="fa-solid fa-user"></i> Geral</span>
                                        </label>
                                        
                                        <?php if($valUmaSocio > 0 || $valDuasSocio > 0): ?>
                                            <label>
                                                <input type="radio" name="tipo_inscricao" value="Socio" <?= ($tipoSalvo == 'Socio') ? 'checked' : '' ?> onchange="atualizarValor(this)">
                                                <span><i class="fa-solid fa-id-card"></i> Sócio / Convênio</span>
                                            </label>
                                        <?php endif; ?>
                                        
                                        <?php if($valUmaEst > 0 || $valDuasEst > 0): ?>
                                            <label>
                                                <input type="radio" name="tipo_inscricao" value="Estudante" <?= ($tipoSalvo == 'Estudante') ? 'checked' : '' ?> onchange="atualizarValor(this)">
                                                <span><i class="fa-solid fa-graduation-cap"></i> Estudante</span>
                                            </label>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="box-divisao">
                                    <strong style="display: block; margin-bottom: 10px; font-size: 13px; color: #333;">
                                        <i class="fa-solid fa-list-check"></i> Escolha suas Categorias:
                                    </strong>
                                    
                                    <?php if (!$torneio['tem_categorias_configuradas']): ?>
                                        <span style="color: #856404; font-size: 13px; font-weight: bold; display: block; text-align: center; padding: 10px; background: #fff3cd; border-radius: 4px; border: 1px solid #ffeeba;">
                                            ⚠️ O organizador ainda não vinculou nenhuma categoria a este torneio.
                                        </span>
                                    
                                    <?php elseif (!empty($torneio['categorias_elegiveis'])): ?>
                                        <?php foreach ($torneio['categorias_elegiveis'] as $cat): 
                                            $checked = in_array($cat['id'], $arrayInscritas) ? 'checked' : '';
                                        ?>
                                            <label>
                                                <input type="checkbox" name="categorias_selecionadas[]" value="<?= $cat['id'] ?>" <?= $checked ?> style="width: 18px; height: 18px; cursor: pointer;" onclick="atualizarValor(this)"> 
                                                <?= $cat['nome'] ?>
                                            </label>
                                        <?php endforeach; ?>
                                        <small style="color: #888; font-size: 11px; display: block; margin-top: 8px; text-align: center;">
                                            * Limite de 2 categorias por atleta.
                                        </small>
                                    
                                    <?php else: ?>
                                        <span style="color: #dc3545; font-size: 13px; font-weight: bold; display: block; text-align: center; padding: 10px; background: #fff; border-radius: 4px;">
                                            🚫 Sem categorias compatíveis com sua Idade/Rating.
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($torneio['categorias_elegiveis'])): ?>
                                    <div class="box-pix">
                                        <p><strong>1 Cat:</strong> R$ <span class="lbl-val-uma"><?= number_format($valUmaGeral, 2, ',', '.') ?></span> | <strong>2 Cat:</strong> R$ <span class="lbl-val-duas"><?= number_format($valDuasGeral, 2, ',', '.') ?></span></p>
                                        <p>Chave PIX do Organizador:</p>
                                        <div class="chave"><?= !empty($torneio['chave_pix']) ? $torneio['chave_pix'] : 'Não informada' ?></div>
                                        
                                        <div class="total-pagar">
                                            Total a Pagar: R$ <span class="valor-dinamico"><?= number_format($valorInicial, 2, ',', '.') ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($torneio['categorias_elegiveis'])): ?>
                                    <?php if ($jaInscrito): ?>
                                        <button type="submit" name="AdmsAtualizar" value="Atualizar" class="btn-atualizar">
                                            <i class="fa-solid fa-rotate"></i> Atualizar Inscrição
                                        </button>
                                        <button type="submit" name="AdmsCancelar" value="Cancelar" class="btn-cancelar" onclick="return confirm('Tem certeza que deseja cancelar sua participação neste torneio?');">
                                            <i class="fa-solid fa-xmark"></i> Cancelar Inscrição
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="AdmsInscrever" value="Inscrever" class="btn-inscrever">
                                            <i class="fa-solid fa-check-double"></i> Confirmar Inscrição!
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="background: #fff; padding: 40px; border-radius: 8px; text-align: center; color: #888; border: 2px dashed #ddd; margin-top: 20px;">
                <i class="fa-solid fa-calendar-xmark" style="font-size: 40px; color: #ccc; margin-bottom: 15px;"></i><br>
                <h3 style="color: #555; margin-bottom: 5px;">A Mesa está Vazia</h3>
                Nenhum clube lançou torneios com inscrições abertas no momento.<br>Continue treinando e volte em breve!
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function atualizarValor(elemento) {
        const form = elemento.closest('form');
        const marcados = form.querySelectorAll('input[type="checkbox"][name="categorias_selecionadas[]"]:checked').length;
        const tipoEscolhido = form.querySelector('input[type="radio"][name="tipo_inscricao"]:checked').value;
        
        const displayTotal = form.querySelector('.valor-dinamico');
        const lblUma = form.querySelector('.lbl-val-uma');
        const lblDuas = form.querySelector('.lbl-val-duas');
        
        if (marcados > 2 && elemento.type === 'checkbox') {
            elemento.checked = false; 
            alert('Atenção: O regulamento permite apenas 2 inscrições por torneio!');
            return atualizarValor(elemento);
        }

        let precoUma = parseFloat(form.getAttribute('data-uma-geral'));
        let precoDuas = parseFloat(form.getAttribute('data-duas-geral'));

        if (tipoEscolhido === 'Socio') {
            precoUma = parseFloat(form.getAttribute('data-uma-socio'));
            precoDuas = parseFloat(form.getAttribute('data-duas-socio'));
        } else if (tipoEscolhido === 'Estudante') {
            precoUma = parseFloat(form.getAttribute('data-uma-est'));
            precoDuas = parseFloat(form.getAttribute('data-duas-est'));
        }

        if(lblUma) lblUma.innerHTML = precoUma.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        if(lblDuas) lblDuas.innerHTML = precoDuas.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        let total = 0;
        if (marcados === 1) total = precoUma;
        if (marcados === 2) total = precoDuas;

        if(displayTotal) {
            displayTotal.innerHTML = total.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
    }

    // DOCAN FIX: Garante que os preços são recalculados ao carregar a página com o valor que veio do banco!
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('form').forEach(form => {
            const radioAtivo = form.querySelector('input[type="radio"]:checked');
            if(radioAtivo) atualizarValor(radioAtivo);
        });
    });
</script>