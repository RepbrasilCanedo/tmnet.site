<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$detalhes = $this->data['viewComp']['detalhes'];
$partidas = $this->data['viewComp']['partidas'];
$progresso = $this->data['viewComp']['status_progresso'];

$isFinished = $progresso['is_finished'];
$hasGrupos = $progresso['has_grupos'];
$hasMataMata = $progresso['has_matamata'];

// ========================================================================
// VERIFICA SE EXISTEM JOGOS DE GRUPO PENDENTES PARA BLOQUEAR O MATA-MATA
// ========================================================================
$pendenciasGrupos = false;
if (!empty($partidas)) {
    foreach ($partidas as $p) {
        if (stripos($p['fase'], 'Grupo') !== false && empty($p['vencedor'])) {
            $pendenciasGrupos = true;
            break;
        }
    }
}
// Desativa o botão se já tem mata-mata, se já acabou, se NÃO tem grupos, OU se tem jogos pendentes!
$btnMataMataDisabled = ($hasMataMata || $isFinished || !$hasGrupos || $pendenciasGrupos) ? 'btn-disabled' : '';
$avisoMataMata = $pendenciasGrupos ? "title='Conclua todas as partidas da fase de grupos primeiro!'" : "";


$statusInscricao = $detalhes['status_inscricao'] ?? 1;
if ($statusInscricao == 1) {
    $btnClass = "btn-danger";
    $btnTexto = "🔒 Encerrar Inscrições";
    $btnColor = "#dc3545";
    $statusMsg = "<span style='color: #0044cc; font-weight: bold;'>Inscrições Abertas</span>";
} else {
    $btnClass = "btn-success";
    $btnTexto = "🔓 Reabrir Inscrições";
    $btnColor = "#0044cc";
    $statusMsg = "<span style='color: #dc3545; font-weight: bold;'>Inscrições Encerradas</span>";
}
?>

<style>
    .btn-disabled {
        background-color: #6c757d !important;
        color: #e2e3e5 !important;
        cursor: not-allowed;
        pointer-events: none;
        opacity: 0.6;
        border: 1px solid #6c757d !important;
    }
    .badge-concluido { background: #28a745; color: white; padding: 5px 15px; border-radius: 4px; font-weight: bold; animation: pulse 2s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); } }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">
                Súmula: <?= $detalhes['nome_torneio'] ?>
                <?php if($isFinished): ?>
                    <span class="badge-concluido" style="margin-left: 15px; font-size: 14px;">🏆 COMPETIÇÃO CONCLUÍDA</span>
                <?php endif; ?>
            </span>
            
            <div class="top-list-right">
                <a href="<?= URLADM ?>edit-competicao/index/<?= $detalhes['id'] ?>" class="btn-info" style="background-color: #6c757d; color: white;">✏️ Editar Dados</a>
                
                <a href="<?= URLADM ?>sorteio-grupos/index/<?= $detalhes['id'] ?>" class="btn-info <?= ($hasGrupos || $isFinished) ? 'btn-disabled' : '' ?>" style="background-color: #17a2b8;">Chaveamento</a>
                
                <a href="<?= URLADM ?>gerar-agenda/index/<?= $detalhes['id'] ?>" class="btn-warning <?= $isFinished ? 'btn-disabled' : '' ?>" style="background-color: #28a745;">Mapas de Mesa</a>
                
                <?php if ($statusInscricao == 1): ?>
                    <a href="<?= URLADM ?>gerenciar-inscricoes/index/<?= $detalhes['id'] ?>" class="btn-info <?= $isFinished ? 'btn-disabled' : '' ?>" style="background-color: #0044cc; color: white;">📋 Gerir Inscritos</a>
                <?php endif; ?>
                
                <a href="<?= URLADM ?>gerar-mata-mata/index/<?= $detalhes['id'] ?>" class="btn-warning <?= $btnMataMataDisabled ?>" <?= $avisoMataMata ?> style="background-color: #28a899;">Mata Mata</a>
                
                <a href="<?= URLADM ?>avancar-mata-mata/index/<?= $detalhes['id'] ?>" class="btn-success <?= (!$hasMataMata || $isFinished) ? 'btn-disabled' : '' ?>" style="background-color: #0044cc;">Avançar Fase</a>
                
                <a href="<?= URLADM ?>gerar-fichas-pdf/index/<?= $detalhes['id'] ?>" target="_blank" class="btn-info <?= $isFinished ? 'btn-disabled' : '' ?>" style="background-color: #17a2b8; color: white;">🖨️ Imprimir Fichas</a>
                
                <a href="<?= URLADM ?>gerar-pdf-sumula/index/<?= $detalhes['id'] ?>" class="btn-warning" target="_blank" style="background-color: #ff9800; color: white;">📄 Imprimir Súmula</a>
                
                <a href="<?= URLADM ?>add-partidas/index/<?= $detalhes['id'] ?>" class="btn-success <?= $isFinished ? 'btn-disabled' : '' ?>">Lançar Novo Jogo</a>
                
                <a href="<?= URLADM ?>view-chave/index/<?= $detalhes['id'] ?>" class="btn-info" style="background-color: #6f42c1; color: white;">🏆 Ver Chave Gráfica</a>

                <a href="<?= URLADM ?>painel-jogos/index/<?= $detalhes['id'] ?>" class="btn-info" target="_blank" style="background-color: #ef4444; color: white;">📺 Abrir Modo TV</a>
                
                <a href="<?= URLADM ?>list-competicoes/index" class="btn-info">Voltar</a>
            </div>
        </div>

        <div class="content-adm" style="background-color: <?= $isFinished ? '#e8f5e9' : '#f8f9fa' ?>; border-left: 5px solid <?= $isFinished ? '#28a745' : '#0044cc' ?>; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; padding: 15px;">
            <p style="margin: 0;">
                <strong>Data:</strong> <?= date('d/m/Y', strtotime($detalhes['data_evento'])) ?> | 
                <strong>Local:</strong> <?= $detalhes['local_evento'] ?> | 
                <strong>Categoria:</strong> <?= $detalhes['categoria_cbtm'] ?> | 
                <strong>Peso:</strong> x<?= number_format($detalhes['fator_multiplicador'], 2) ?>
            </p>
            
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 14px;">Status: <?= $statusMsg ?></span>
                <a href="<?= URLADM ?>alt-status-inscricao/index/<?= $detalhes['id'] ?>" class="<?= $btnClass ?> <?= $isFinished ? 'btn-disabled' : '' ?>" style="background-color: <?= $btnColor ?>; color: white; padding: 6px 15px; border-radius: 4px; text-decoration: none; font-size: 14px; font-weight: bold;">
                    <?= $btnTexto ?>
                </a>
            </div>
        </div>

        <table class="list-table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Fase</th>
                    <th style="text-align: right;">Atleta A</th>
                    <th style="text-align: center;">Placar</th>
                    <th style="text-align: left;">Atleta B</th>
                    <th>Vencedor</th>
                    <th style="text-align: center;">Pts</th>
                    <?php if(!$isFinished): ?>
                        <th style="text-align: center;">Ações</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($partidas)): ?>
                    <?php foreach ($partidas as $partida): ?>
                        <tr>
                            <td>
                                <?php if (!empty($partida['cat_nome'])): ?>
                                    <span style="background: #17a2b8; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px;">
                                        <?= $partida['cat_nome'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td><small><?= $partida['fase'] ?></small></td>
                            <td style="text-align: right;"><strong><?= $partida['atleta_a'] ?></strong></td>
                            <td style="text-align: center;">
                                <span style="background: #333; color: #fff; padding: 3px 8px; border-radius: 4px;">
                                    <?= $partida['sets_atleta_a'] ?? 0 ?> x <?= $partida['sets_atleta_b'] ?? 0 ?>
                                </span>
                            </td>
                            <td style="text-align: left;"><strong><?= $partida['atleta_b'] ?></strong></td>
                            <td>
                                <?php if($partida['vencedor']): ?>
                                    <span style="color: #28a745;">✔ <?= $partida['vencedor'] ?></span>
                                <?php else: ?>
                                    <span style="color: #666;"><small>Em aberto</small></span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;"><small>+<?= $partida['pontos_ganhos'] ?? 0 ?></small></td>
                            
                            <?php if(!$isFinished): ?>
                                <td style="text-align: center;">
                                    <a href="<?= URLADM ?>edit-partida/index/<?= $partida['id'] ?>" class="btn-info" style="padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px; margin-right: 5px;">Editar</a>
                                    <a href="<?= URLADM ?>delete-partida/index/<?= $partida['id'] ?>" class="btn-danger" style="background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; text-decoration: none; font-size: 12px;" onclick="return confirm('Tem certeza que deseja apagar este resultado? Os pontos serão descontados do ranking.');">Excluir</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="<?= $isFinished ? '7' : '8' ?>" style="text-align: center;">Nenhuma partida registrada neste torneio.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>