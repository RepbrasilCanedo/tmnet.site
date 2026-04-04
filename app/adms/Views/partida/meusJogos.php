<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<style>
    .mobile-container { max-width: 600px; margin: 0 auto; }
    .jogo-card { background: #fff; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; overflow: hidden; border-top: 4px solid #0044cc; }
    .jogo-header { background: #f8f9fa; padding: 10px 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .badge-mesa { background: #0044cc; color: white; padding: 3px 8px; border-radius: 12px; font-weight: bold; font-size: 12px; }
    .badge-hora { font-weight: bold; color: #333; font-size: 14px; }
    
    .jogo-body { padding: 15px; text-align: center; }
    .torneio-nome { font-size: 12px; color: #666; margin-bottom: 5px; display: block; text-transform: uppercase; letter-spacing: 1px; }
    .fase-nome { font-size: 13px; color: #17a2b8; font-weight: bold; margin-bottom: 15px; display: block; }
    
    .confronto { display: flex; justify-content: center; align-items: center; gap: 15px; font-size: 16px; font-weight: bold; color: #333; }
    .vs-text { color: #dc3545; font-size: 14px; }
    
    .jogo-footer { padding: 15px; background: #fff; border-top: 1px solid #eee; }
    .btn-lancar-mobile { display: block; width: 100%; background: #28a745; color: white; text-align: center; padding: 12px 0; border-radius: 6px; text-decoration: none; font-size: 16px; font-weight: bold; box-shadow: 0 2px 4px rgba(40,167,69,0.3); }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">📋 Meus Jogos (Árbitro)</span>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <div class="content-adm mobile-container" style="background: transparent; box-shadow: none; padding: 0;">
            
            <?php if (!empty($this->data['jogos'])): ?>
                
                <p style="color: #555; text-align: center; margin-bottom: 20px;">Você tem <strong><?= count($this->data['jogos']) ?></strong> jogo(s) agendado(s) para você.</p>

                <?php foreach ($this->data['jogos'] as $jogo): 
                    $divisao = !empty($jogo['div_nome']) ? " - " . $jogo['div_nome'] : "";
                ?>
                    <div class="jogo-card">
                        <div class="jogo-header">
                            <span class="badge-mesa">MESA <?= $jogo['mesa'] ?></span>
                            <span class="badge-hora">⌚ <?= !empty($jogo['horario_previsto']) ? date('H:i', strtotime($jogo['horario_previsto'])) : 'A definir' ?></span>
                        </div>
                        
                        <div class="jogo-body">
                            <span class="torneio-nome"><?= $jogo['nome_torneio'] ?></span>
                            <span class="fase-nome"><?= $jogo['fase'] ?><?= $divisao ?></span>
                            
                            <div class="confronto">
                                <div style="flex: 1; text-align: right;"><?= $jogo['atleta_a'] ?></div>
                                <div class="vs-text">VS</div>
                                <div style="flex: 1; text-align: left;"><?= $jogo['atleta_b'] ?></div>
                            </div>
                        </div>

                        <div class="jogo-footer">
                            <a href="<?= URLADM ?>edit-partida/index/<?= $jogo['id'] ?>" class="btn-lancar-mobile">
                                📝 Iniciar Súmula Eletrônica
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div style="background: #fff; padding: 30px 20px; border-radius: 8px; text-align: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="color: #0044cc; margin-top: 0;">Nenhum jogo na agenda!</h3>
                    <p style="color: #666; font-size: 15px;">Você não possui partidas atribuídas a si no momento. Aguarde as instruções da organização.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>