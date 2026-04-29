<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$torneiosClube = $this->data['torneios'] ?? [];
$inscritos = $this->data['inscritos'] ?? [];
$torneioAtivo = $this->data['torneio_selecionado'] ?? null;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .filtro-box { background: #f8f9fa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
    .table-inscritos { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; }
    .table-inscritos th { background: #0044cc; color: white; padding: 12px 15px; text-align: left; font-size: 14px; text-transform: uppercase; }
    .table-inscritos td { padding: 12px 15px; border-bottom: 1px solid #eee; vertical-align: middle; font-size: 14px; color: #333; }
    .table-inscritos tr:hover { background: #fdfdfd; }
    
    .badge-status { padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; text-align: center; display: inline-block; width: 100%; max-width: 120px;}
    .status-1 { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; } /* Aguardando */
    .status-2 { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; } /* Pago */
    .status-3 { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; } /* Isento */
    
    .btn-acao { border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; font-size: 13px; font-weight: bold; color: white; display: inline-flex; align-items: center; gap: 5px; transition: 0.2s; text-decoration: none;}
    .btn-aprovar { background-color: #28a745; } .btn-aprovar:hover { background-color: #218838; }
    .btn-pendente { background-color: #ffc107; color: #333; } .btn-pendente:hover { background-color: #e0a800; }
    .btn-zap { background-color: #25D366; } .btn-zap:hover { background-color: #1ebe57; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">📋 Gerir Inscrições e Pagamentos</span>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <div class="filtro-box">
            <form method="GET" action="<?= URLADM ?>gerir-inscricoes/index" style="display: flex; gap: 15px; width: 100%; align-items: flex-end;">
                <div style="flex-grow: 1; max-width: 400px;">
                    <label style="font-size: 13px; font-weight: bold; color: #555; display: block; margin-bottom: 5px;">Selecione o Torneio para Gerir:</label>
                    <select name="comp" class="input-adm" style="margin: 0;" required onchange="this.form.submit()">
                        <option value="">-- Selecione --</option>
                        <?php foreach ($torneiosClube as $tc): ?>
                            <option value="<?= $tc['id'] ?>" <?= ($torneioAtivo == $tc['id']) ? 'selected' : '' ?>>
                                <?= date('d/m/y', strtotime($tc['data_evento'])) ?> - <?= $tc['nome_torneio'] ?> 
                                <?= ($tc['status_inscricao'] == 0) ? '(Fechado)' : '(Aberto)' ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <?php if ($torneioAtivo): ?>
            <div style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #0044cc; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="margin: 0; color: #0044cc;">Total de Inscritos: <?= count($inscritos) ?> atleta(s)</h3>
                
                <a href="<?= URLADM ?>gerar-pdf-inscritos/index/<?= $torneioAtivo ?>" target="_blank" class="btn-acao" style="background: #333; padding: 10px 20px; font-size: 14px;">
                    <i class="fa-solid fa-file-pdf" style="color: #ff4757;"></i> Imprimir Relatório
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table class="table-inscritos">
                    <thead>
                        <tr>
                            <th>Atleta</th>
                            <th>Contato</th>
                            <th>Categorias Disputadas</th>
                            <th style="text-align: center;">Valor a Pagar</th>
                            <th style="text-align: center;">Status do PIX</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($inscritos)): ?>
                            <?php foreach ($inscritos as $ins): 
                                $lblModalidade = 'Geral';
                                if($ins['tipo_inscricao'] === 'Socio') $lblModalidade = 'Sócio/Convênio';
                                if($ins['tipo_inscricao'] === 'Estudante') $lblModalidade = 'Estudante';
                            ?>
                                <tr>
                                    <td><strong><?= $ins['atleta'] ?></strong></td>
                                    <td>
                                        <?= $ins['telefone_display'] ?><br>
                                        <?php if(!empty($ins['telefone_limpo'])): ?>
                                            <a href="https://wa.me/55<?= $ins['telefone_limpo'] ?>" target="_blank" class="btn-acao btn-zap" style="padding: 2px 6px; font-size: 11px; margin-top: 4px;">
                                                <i class="fa-brands fa-whatsapp"></i> Chamar
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $ins['categorias_str'] ?></td>
                                    <td style="text-align: center; font-weight: bold; color: #0044cc;">
                                        R$ <?= number_format($ins['valor_total'], 2, ',', '.') ?><br>
                                        <span style="font-size: 10px; color: #666; font-weight: normal; background: #eee; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px;">
                                            <?= $lblModalidade ?>
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        <?php if($ins['status_pagamento_id'] == 1): ?>
                                            <span class="badge-status status-1"><i class="fa-solid fa-hourglass-half"></i> Aguardando</span>
                                        <?php elseif($ins['status_pagamento_id'] == 2): ?>
                                            <span class="badge-status status-2"><i class="fa-solid fa-check-double"></i> Confirmado</span>
                                        <?php else: ?>
                                            <span class="badge-status status-3"><i class="fa-solid fa-star"></i> Isento</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: center;">
                                        <form method="POST" action="" style="display: flex; gap: 5px; justify-content: center; margin: 0;">
                                            <input type="hidden" name="user_id" value="<?= $ins['user_id'] ?>">
                                            <input type="hidden" name="comp_id" value="<?= $torneioAtivo ?>">
                                            
                                            <?php if($ins['status_pagamento_id'] == 1): ?>
                                                <button type="submit" name="AcaoStatus" value="aprovar" class="btn-acao btn-aprovar" title="Confirmar Recebimento PIX">
                                                    <i class="fa-solid fa-check"></i> Aprovar
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" name="AcaoStatus" value="pendente" class="btn-acao btn-pendente" title="Voltar para Aguardando Pagamento" onclick="return confirm('Tem certeza que deseja remover o status de Pago deste atleta?');">
                                                    <i class="fa-solid fa-rotate-left"></i> Desfazer
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #888;">
                                    Nenhum atleta inscrito neste torneio até o momento.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: #fff; border-radius: 8px; color: #666; border: 2px dashed #ddd;">
                <i class="fa-solid fa-arrow-up-from-bracket" style="font-size: 30px; color: #ccc; margin-bottom: 10px;"></i><br>
                Selecione um torneio no menu acima para gerir as inscrições e os pagamentos.
            </div>
        <?php endif; ?>

    </div>
</div>