<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$detalhes = $this->data['viewChave']['detalhes'];
$categorias = $this->data['viewChave']['chave'];
?>

<style>
    /* MOTOR GRÁFICO DA CHAVE - ALINHAMENTO FLEXBOX PERFEITO */
    .bracket-container { width: 100%; overflow-x: auto; padding: 20px; background: #f4f6f9; border-radius: 8px; }
    
    .bracket-wrapper { display: flex; flex-direction: row; align-items: stretch; min-width: max-content; }

    /* Coluna de uma Fase (Quartas, Semis, Final) */
    .bracket-round { display: flex; flex-direction: column; width: 240px; margin-right: 50px; }
    .bracket-round:last-child { margin-right: 0; }
    
    .round-title { text-align: center; background: #0044cc; color: white; padding: 8px; border-radius: 4px; font-weight: bold; font-size: 14px; text-transform: uppercase; margin-bottom: 15px; position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    /* Container elástico que alinha as partidas perfeitamente */
    .round-matches { display: flex; flex-direction: column; flex: 1; }

    /* O invólucro do jogo que divide o espaço da tela (A MÁGICA ACONTECE AQUI) */
    .match-wrapper { display: flex; flex-direction: column; justify-content: center; flex: 1; position: relative; padding: 10px 0; min-height: 80px; }

    /* A caixa visual do Jogo */
    .bracket-match { background: #fff; border: 1px solid #caced1; border-radius: 6px; position: relative; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 2; }

    /* ==========================================
       CONECTORES (LINHAS) - CSS PURO
       ========================================== */
    
    /* Linha horizontal saindo (Para a direita) */
    .bracket-round:not(:last-child) .match-wrapper::after { content: ""; position: absolute; right: -25px; top: 50%; width: 25px; border-top: 2px solid #adb5bd; z-index: 1; }
    
    /* Linha vertical Descendo (Para os jogos Ímpares) */
    .bracket-round:not(:last-child) .match-wrapper:nth-child(odd)::before { content: ""; position: absolute; right: -25px; top: 50%; height: 50%; border-right: 2px solid #adb5bd; z-index: 1; }
    
    /* Linha vertical Subindo (Para os jogos Pares) */
    .bracket-round:not(:last-child) .match-wrapper:nth-child(even)::before { content: ""; position: absolute; right: -25px; top: 0; height: 50%; border-right: 2px solid #adb5bd; z-index: 1; }
    
    /* Linha horizontal entrando (Para a esquerda do próximo jogo) */
    .bracket-round:not(:first-child) .bracket-match::before { content: ""; position: absolute; left: -25px; top: 50%; width: 25px; border-top: 2px solid #adb5bd; z-index: 1; }

    /* ==========================================
       ESTILOS INTERNOS DO JOGO
       ========================================== */
    .match-player { display: flex; justify-content: space-between; align-items: center; padding: 10px 12px; border-bottom: 1px solid #eee; }
    .match-player:last-child { border-bottom: none; }
    
    .player-name { font-size: 13px; font-weight: 500; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
    .player-name small { color: #888; font-size: 11px; margin-left: 4px; }

    .player-score { background: #e9ecef; color: #495057; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 14px; min-width: 25px; text-align: center; }
    
    /* Destaque para o Vencedor */
    .winner .player-name { color: #0044cc; font-weight: bold; }
    .winner .player-name::before { content: "🏆 "; font-size: 14px; }
    .winner .player-score { background: #0044cc; color: #fff; }

    /* Jogo A Definir */
    .tbd-player { color: #999; font-style: italic; font-size: 12px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Diagrama Mata-Mata: <?= $detalhes['nome_torneio'] ?></span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['competicao_id'] ?>" class="btn-info">Voltar à Súmula</a>
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

        <?php if (!empty($categorias)): ?>
            <?php foreach ($categorias as $cat): ?>
                <div class="content-adm" style="margin-bottom: 30px; padding: 0; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
                    
                    <div style="background: #fff; padding: 15px 20px; border-bottom: 1px solid #eee;">
                        <h3 style="margin: 0; color: #333; font-size: 18px; border-left: 4px solid #0044cc; padding-left: 10px;">
                            <?= $cat['titulo'] ?>
                        </h3>
                    </div>

                    <div class="bracket-container">
                        <div class="bracket-wrapper">
                            
                            <?php foreach ($cat['fases'] as $fase): ?>
                                <div class="bracket-round">
                                    <div class="round-title"><?= $fase['nome'] ?></div>
                                    
                                    <div class="round-matches">
                                        <?php foreach ($fase['jogos'] as $jogo): ?>
                                            
                                            <div class="match-wrapper">
                                                <div class="bracket-match">
                                                    
                                                    <div class="match-player <?= ($jogo['vencedor_id'] == $jogo['atleta_a_id'] && $jogo['vencedor_id'] > 0) ? 'winner' : '' ?>">
                                                        <span class="player-name">
                                                            <?= $jogo['atleta_a_nome'] ?? '<span class="tbd-player">A Definir</span>' ?>
                                                            <?php if(!empty($jogo['atleta_a_apelido'])): ?><small>(<?= $jogo['atleta_a_apelido'] ?>)</small><?php endif; ?>
                                                        </span>
                                                        <span class="player-score"><?= $jogo['sets_atleta_a'] ?? 0 ?></span>
                                                    </div>

                                                    <div class="match-player <?= ($jogo['vencedor_id'] == $jogo['atleta_b_id'] && $jogo['vencedor_id'] > 0) ? 'winner' : '' ?>">
                                                        <span class="player-name">
                                                            <?= $jogo['atleta_b_nome'] ?? '<span class="tbd-player">A Definir</span>' ?>
                                                            <?php if(!empty($jogo['atleta_b_apelido'])): ?><small>(<?= $jogo['atleta_b_apelido'] ?>)</small><?php endif; ?>
                                                        </span>
                                                        <span class="player-score"><?= $jogo['sets_atleta_b'] ?? 0 ?></span>
                                                    </div>
                                                    
                                                    <a href="<?= URLADM ?>edit-partida/index/<?= $jogo['id'] ?>" title="Lançar Placar" style="position: absolute; top: -10px; right: -10px; background: #ffc107; color: #212529; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 12px; z-index: 5; box-shadow: 0 2px 4px rgba(0,0,0,0.2); border: 2px solid #fff;">✏️</a>
                                                </div>
                                            </div>

                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="margin-top: 20px; padding: 40px; text-align: center; background: #fff; border: 1px dashed #ccc; border-radius: 8px;">
                <p style="color: #666; font-size: 16px;">O Mata-Mata ainda não foi gerado para esta competição.</p>
                <p style="color: #888; font-size: 14px;">Vá à Súmula e clique em 'Mata Mata' quando a Fase de Grupos terminar.</p>
            </div>
        <?php endif; ?>

    </div>
</div>