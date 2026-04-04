<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$torneioNome = $this->data['painel']['nome_torneio'];
$jogos = $this->data['painel']['jogos'];
?>

<style>
    /* SOBREPÕE O TEMA DO ADMIN PARA CRIAR UM MODO TV FULLSCREEN */
    .painel-tv-fullscreen {
        position: fixed;
        top: 0; left: 0; width: 100vw; height: 100vh;
        background-color: #0f172a;
        z-index: 99999;
        overflow-y: auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f8fafc;
        padding: 20px 40px;
        box-sizing: border-box;
    }

    .painel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 2px solid #334155;
        padding-bottom: 15px;
        margin-bottom: 30px;
    }

    .painel-title { font-size: 32px; font-weight: 900; color: #38bdf8; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
    
    .painel-clock { font-size: 24px; font-weight: bold; color: #cbd5e1; background: #1e293b; padding: 5px 15px; border-radius: 8px; border: 1px solid #475569; }

    .grid-mesas-tv {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(450px, 1fr));
        gap: 30px;
    }

    .mesa-tv-card {
        background: #1e293b;
        border-radius: 12px;
        border-top: 6px solid #10b981;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .mesa-tv-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .mesa-tv-num { font-size: 28px; font-weight: 900; color: #10b981; margin: 0; }
    
    .mesa-tv-cat { background: #3b82f6; color: white; padding: 4px 10px; border-radius: 6px; font-size: 14px; font-weight: bold; text-transform: uppercase; }

    .mesa-tv-fase { font-size: 15px; color: #94a3b8; margin-bottom: 15px; border-bottom: 1px solid #334155; padding-bottom: 10px; }

    .mesa-tv-jogadores { display: flex; flex-direction: column; gap: 10px; }
    
    .tv-jogador { font-size: 26px; font-weight: bold; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    .tv-vs { font-size: 20px; color: #ef4444; font-weight: 900; text-align: left; padding-left: 20px; }

    .btn-sair-tv { position: absolute; top: 20px; left: 50%; transform: translateX(-50%); background: #ef4444; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 12px; opacity: 0; transition: opacity 0.3s; z-index: 100000; }
    .painel-tv-fullscreen:hover .btn-sair-tv { opacity: 1; }

    .sem-jogos { text-align: center; margin-top: 100px; color: #64748b; }
    .sem-jogos h2 { font-size: 40px; }
</style>

<div class="painel-tv-fullscreen" id="telaPainel">
    
    <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['competicao_id'] ?>" class="btn-sair-tv">🔙 Sair do Modo TV</a>

    <div class="painel-header">
        <h1 class="painel-title">🔴 EM ANDAMENTO: <?= $torneioNome ?></h1>
        <div style="display: flex; gap: 15px; align-items: center;">
            <button onclick="toggleFullScreen()" style="background: #334155; border: none; color: white; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold;">⛶ Ecrã Inteiro</button>
            <div class="painel-clock" id="relogioPainel">00:00:00</div>
        </div>
    </div>

    <?php if (!empty($jogos)): ?>
        <div class="grid-mesas-tv">
            <?php foreach ($jogos as $jogo): 
                $catNome = !empty($jogo['cat_nome']) ? $jogo['cat_nome'] : 'Livre';
            ?>
                <div class="mesa-tv-card">
                    <div class="mesa-tv-header">
                        <h2 class="mesa-tv-num">MESA <?= $jogo['mesa'] ?></h2>
                        <span class="mesa-tv-cat"><?= $catNome ?></span>
                    </div>
                    
                    <div class="mesa-tv-fase">
                        Fase: <strong><?= $jogo['fase'] ?></strong> 
                        <span style="float: right; color: #f59e0b;">⌚ <?= !empty($jogo['horario_previsto']) ? date('H:i', strtotime($jogo['horario_previsto'])) : 'A definir' ?></span>
                    </div>

                    <div class="mesa-tv-jogadores">
                        <div class="tv-jogador">🏓 <?= $jogo['atleta_a'] ?></div>
                        <div class="tv-vs">VS</div>
                        <div class="tv-jogador">🏓 <?= $jogo['atleta_b'] ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="sem-jogos">
            <h2>🎾 Nenhuma partida em andamento no momento.</h2>
            <p style="font-size: 20px;">Aguarde o chamado da arbitragem.</p>
        </div>
    <?php endif; ?>
</div>

<script>
    // Atualiza a página a cada 15 segundos para buscar novos jogos
    setTimeout(function() {
        window.location.reload(1);
    }, 15000);

    // Relógio em tempo real
    function atualizarRelogio() {
        const agora = new Date();
        const horas = String(agora.getHours()).padStart(2, '0');
        const minutos = String(agora.getMinutes()).padStart(2, '0');
        const segundos = String(agora.getSeconds()).padStart(2, '0');
        document.getElementById('relogioPainel').innerText = horas + ':' + minutos + ':' + segundos;
    }
    setInterval(atualizarRelogio, 1000);
    atualizarRelogio();

    // Função para ativar Ecrã Inteiro no navegador
    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Erro ao tentar entrar em modo ecrã inteiro: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>