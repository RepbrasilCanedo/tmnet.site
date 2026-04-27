<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$torneioNome = $this->data['painel']['nome_torneio'];
$jogos = $this->data['painel']['jogos'] ?? [];
$podios = $this->data['painel']['podios'] ?? [];
$isFinished = $this->data['painel']['is_finished'] ?? false;

// ========================================================================
// DOCAN LOGIC: AGRUPAR JOGOS POR MESA (Criação da "Fila de Espera")
// ========================================================================
$mesasAgrupadas = [];
if (!empty($jogos)) {
    foreach ($jogos as $j) {
        $mesaNum = $j['mesa'] ?? '0';
        $mesasAgrupadas[$mesaNum][] = $j;
    }
}

// Para controlo do AJAX (Se a lista de jogos ativos mudar, damos refresh na tela)
$idsAtuais = implode(',', array_column($jogos, 'id'));
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .painel-tv-fullscreen {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background-color: #0f172a; z-index: 99999; overflow-y: auto;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #f8fafc; padding: 20px 40px; box-sizing: border-box;
    }

    .painel-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #334155; padding-bottom: 15px; margin-bottom: 30px; }
    .painel-title { font-size: 32px; font-weight: 900; color: #38bdf8; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
    .painel-clock { font-size: 24px; font-weight: bold; color: #cbd5e1; background: #1e293b; padding: 5px 15px; border-radius: 8px; border: 1px solid #475569; }

    .grid-mesas-tv { display: grid; grid-template-columns: repeat(auto-fill, minmax(450px, 1fr)); gap: 30px; }
    .mesa-tv-card { background: #1e293b; border-radius: 12px; border-top: 6px solid #10b981; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5); padding: 20px; position: relative; display: flex; flex-direction: column; }
    
    .mesa-tv-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .mesa-tv-num { font-size: 28px; font-weight: 900; color: #10b981; margin: 0; }
    .mesa-tv-cat { background: #3b82f6; color: white; padding: 4px 10px; border-radius: 6px; font-size: 14px; font-weight: bold; text-transform: uppercase; }
    
    .mesa-tv-fase { display: flex; justify-content: space-between; font-size: 15px; color: #94a3b8; margin-bottom: 15px; border-bottom: 1px solid #334155; padding-bottom: 10px; }
    
    .tv-scoreboard { display: flex; flex-direction: column; gap: 8px; }
    .tv-player-row { display: flex; justify-content: space-between; align-items: center; background: #0f172a; padding: 10px 15px; border-radius: 8px; }
    .tv-jogador { font-size: 24px; font-weight: bold; color: #ffffff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 60%; }
    
    .tv-score-boxes { display: flex; gap: 10px; align-items: center; }
    .tv-sets { background: #334155; color: #cbd5e1; font-size: 20px; font-weight: bold; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 6px; }
    .tv-pts { background: #10b981; color: #0f172a; font-size: 28px; font-weight: 900; width: 55px; height: 50px; display: flex; align-items: center; justify-content: center; border-radius: 6px; box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
    
    .status-live { color: #ef4444; font-weight: bold; font-size: 12px; display: flex; align-items: center; gap: 5px; }

    /* DOCAN FIX: Estilos da Fila de Espera (Próximos Jogos) */
    .fila-jogos { margin-top: 20px; border-top: 1px dashed #334155; padding-top: 15px; }
    .fila-titulo { font-size: 13px; color: #94a3b8; text-transform: uppercase; font-weight: bold; margin-bottom: 10px; }
    .fila-item { display: flex; align-items: center; background: #0f172a; padding: 8px 12px; border-radius: 6px; margin-bottom: 6px; font-size: 14px; color: #cbd5e1; border-left: 3px solid #3b82f6; }
    .fila-hora { color: #38bdf8; font-weight: bold; margin-right: 15px; font-size: 13px; }
    .fila-atletas { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .btn-sair-tv { position: absolute; top: 20px; left: 50%; transform: translateX(-50%); background: #ef4444; color: white; padding: 8px 15px; border-radius: 20px; text-decoration: none; font-weight: bold; font-size: 12px; opacity: 0; transition: opacity 0.3s; z-index: 100000; }
    .painel-tv-fullscreen:hover .btn-sair-tv { opacity: 1; }

    .banner-marketing { display: flex; justify-content: center; align-items: center; width: 100%; margin-top: 20px; margin-bottom: 40px; }
    .banner-marketing img { max-width: 90%; max-height: 60vh; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5); object-fit: contain; }

    .podio-container { margin-top: 50px; border-top: 2px dashed #334155; padding-top: 40px; padding-bottom: 40px;}
    .podio-title { text-align: center; color: #fbbf24; font-size: 32px; font-weight: 900; letter-spacing: 2px; margin-bottom: 30px; text-transform: uppercase; text-shadow: 0 2px 10px rgba(251, 191, 36, 0.3); }
    .grid-podios { display: flex; flex-wrap: wrap; gap: 25px; justify-content: center; }
    .podio-card { background: linear-gradient(145deg, #1e293b, #0f172a); border: 1px solid #fbbf24; border-radius: 12px; padding: 20px 30px; min-width: 320px; box-shadow: 0 10px 25px rgba(251, 191, 36, 0.15); }
    .podio-cat { font-size: 16px; color: #94a3b8; text-transform: uppercase; margin-bottom: 15px; border-bottom: 1px solid #334155; padding-bottom: 8px; font-weight: bold; }
    .podio-gold { font-size: 26px; font-weight: bold; color: #fbbf24; margin-bottom: 12px; }
    .podio-silver { font-size: 20px; color: #cbd5e1; font-weight: 500; margin-bottom: 10px; }
    .podio-bronze { font-size: 18px; color: #cd7f32; font-weight: 500; margin-bottom: 5px; }

    .painel-footer { margin-top: auto; padding-top: 20px; padding-bottom: 20px; text-align: center; border-top: 1px solid #334155; }
    .painel-footer img { max-height: 50px; margin-bottom: 10px; opacity: 0.9; transition: opacity 0.3s; }
    .painel-footer img:hover { opacity: 1; }
    .painel-footer p { color: #64748b; font-size: 13px; margin: 0; letter-spacing: 0.5px; }
</style>

<div class="painel-tv-fullscreen" id="telaPainel" style="display: flex; flex-direction: column;">
    <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['competicao_id'] ?>" class="btn-sair-tv">🔙 Sair do Modo TV</a>

    <div class="painel-header">
        <h1 class="painel-title">
            <?= (empty($mesasAgrupadas) && $isFinished) ? '🏆 COMPETIÇÃO:' : '🔴 TORNEIO EM ANDAMENTO:' ?> <?= $torneioNome ?>
        </h1>
        <div style="display: flex; gap: 15px; align-items: center;">
            <button onclick="toggleFullScreen()" style="background: #334155; border: none; color: white; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: bold;">⛶ Ecrã Inteiro</button>
            <div class="painel-clock" id="relogioPainel">00:00:00</div>
        </div>
    </div>

    <?php if (!empty($mesasAgrupadas)): ?>
        <div class="grid-mesas-tv" id="grid-mesas">
            <?php foreach ($mesasAgrupadas as $mesaNum => $jogosMesa): 
                
                // Pega o jogo ativo (ou o primeiro agendado) para ser o Placar Principal
                $jogoPrincipal = null;
                $proximosJogos = [];
                
                foreach ($jogosMesa as $j) {
                    if (!$jogoPrincipal && ($j['status_partida'] == 'Em Andamento' || $j['status_partida'] == 'Agendado')) {
                        $jogoPrincipal = $j;
                    } else {
                        $proximosJogos[] = $j;
                    }
                }
                
                // Segurança: Se todos estiverem terminados, mostra o último finalizado e remove da lista de próximos
                if (!$jogoPrincipal) {
                    $jogoPrincipal = $jogosMesa[0];
                    array_shift($proximosJogos);
                }

                $catNome = !empty($jogoPrincipal['cat_nome']) ? $jogoPrincipal['cat_nome'] : 'Livre';
                $isLive = ($jogoPrincipal['status_partida'] === 'Em Andamento');
            ?>
                <div class="mesa-tv-card">
                    <div class="mesa-tv-header">
                        <h2 class="mesa-tv-num">MESA <?= $mesaNum ?></h2>
                        <span class="mesa-tv-cat"><?= $catNome ?></span>
                    </div>
                    
                    <div class="mesa-tv-fase">
                        <span>Fase: <strong><?= $jogoPrincipal['fase'] ?></strong></span>
                        <span id="status-badge-<?= $jogoPrincipal['id'] ?>" class="status-live">
                            <?php if($isLive): ?>
                                <i class="fa-solid fa-circle-play fa-fade"></i> AO VIVO
                            <?php elseif($jogoPrincipal['status_partida'] === 'Finalizado'): ?>
                                <span style="color:#10b981;"><i class="fa-solid fa-check"></i> FINALIZADO</span>
                            <?php else: ?>
                                <span style="color:#f59e0b;"><i class="fa-regular fa-clock"></i> <?= !empty($jogoPrincipal['horario_previsto']) ? date('H:i', strtotime($jogoPrincipal['horario_previsto'])) : 'A definir' ?></span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="tv-scoreboard">
                        <div class="tv-player-row">
                            <div class="tv-jogador">🏓 <?= $jogoPrincipal['atleta_a'] ?></div>
                            <div class="tv-score-boxes">
                                <div class="tv-sets" id="sets-a-<?= $jogoPrincipal['id'] ?>"><?= $jogoPrincipal['sets_atleta_a'] ?></div>
                                <div class="tv-pts" id="pts-a-<?= $jogoPrincipal['id'] ?>"><?= $jogoPrincipal['pts_a'] ?></div>
                            </div>
                        </div>
                        <div class="tv-player-row">
                            <div class="tv-jogador">🏓 <?= $jogoPrincipal['atleta_b'] ?></div>
                            <div class="tv-score-boxes">
                                <div class="tv-sets" id="sets-b-<?= $jogoPrincipal['id'] ?>"><?= $jogoPrincipal['sets_atleta_b'] ?></div>
                                <div class="tv-pts" id="pts-b-<?= $jogoPrincipal['id'] ?>"><?= $jogoPrincipal['pts_b'] ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($proximosJogos)): ?>
                        <div class="fila-jogos">
                            <div class="fila-titulo"><i class="fa-solid fa-list-ol"></i> A Seguir nesta mesa:</div>
                            <?php 
                            // Mostra até no máximo os próximos 3 jogos para não desconfigurar o tamanho do card
                            foreach (array_slice($proximosJogos, 0, 3) as $prox): 
                                $horarioProx = !empty($prox['horario_previsto']) ? date('H:i', strtotime($prox['horario_previsto'])) : '--:--';
                            ?>
                                <div class="fila-item">
                                    <span class="fila-hora"><?= $horarioProx ?></span>
                                    <span class="fila-atletas"><?= $prox['atleta_a'] ?> vs <?= $prox['atleta_b'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        
        <div class="banner-marketing">
            <img src="<?= URLADM ?>app/adms/assets/image/painel/marketing/etmpa_torneio.jpeg" alt="Marketing do Torneio" 
                 onerror="this.onerror=null; this.outerHTML='<h2 style=\'color:#ef4444; text-align:center; margin-top: 50px;\'>⚠️ Erro: Imagem não encontrada!<br><small style=\'color:#94a3b8; font-size:16px;\'>Caminho tentado: '+this.src+'</small></h2>';">
        </div>

    <?php endif; ?>

    <?php if (!empty($podios) && $isFinished): ?>
        <div class="podio-container" style="border-top: none; margin-top: 20px;">
            <h2 class="podio-title">🏆 Quadro de Honra / Campeões 🏆</h2>
            <div class="grid-podios">
                <?php foreach ($podios as $podio): ?>
                    <div class="podio-card">
                        <div class="podio-cat"><?= $podio['categoria'] ?></div>
                        <div class="podio-gold">🥇 1º <?= $podio['campeao'] ?></div>
                        <div class="podio-silver">🥈 2º <?= $podio['vice'] ?></div>
                        
                        <?php foreach ($podio['terceiros'] as $terceiro): ?>
                            <div class="podio-bronze">🥉 3º <?= $terceiro ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="painel-footer">
        <img src="<?= URLADM ?>app/adms/assets/image/painel/logo/logo.png" alt="RepBrasil Tecnologia" onerror="this.style.display='none';">
        <p>&copy; 2026 RepBrasil Tecnologia - Todos os direitos reservados - Gerado via TMNet</p>
    </div>

</div>

<script>
    const controleIdsAtuais = "<?= $idsAtuais ?>";

    setInterval(() => {
        fetch(window.location.href + '?ajax=true')
        .then(response => response.json())
        .then(data => {
            if (data.jogos) {
                // Junta os IDs que vieram do servidor
                let novosIds = data.jogos.map(j => j.id).join(',');
                
                // Se algum jogo saiu da lista ou entrou um novo (avançou de fase), DÁ REFRESH TOTAL
                if (controleIdsAtuais !== novosIds) {
                    window.location.reload();
                    return;
                }

                // Caso os jogos sejam os mesmos, atualiza só os pontos suavemente
                data.jogos.forEach(jogo => {
                    let boxSetsA = document.getElementById('sets-a-' + jogo.id);
                    let boxSetsB = document.getElementById('sets-b-' + jogo.id);
                    let boxPtsA = document.getElementById('pts-a-' + jogo.id);
                    let boxPtsB = document.getElementById('pts-b-' + jogo.id);
                    let statusBadge = document.getElementById('status-badge-' + jogo.id);

                    if (boxSetsA) boxSetsA.innerText = jogo.sets_atleta_a;
                    if (boxSetsB) boxSetsB.innerText = jogo.sets_atleta_b;
                    if (boxPtsA) boxPtsA.innerText = jogo.pts_a;
                    if (boxPtsB) boxPtsB.innerText = jogo.pts_b;
                    
                    if (statusBadge && jogo.status_partida === 'Em Andamento') {
                        statusBadge.innerHTML = '<i class="fa-solid fa-circle-play fa-fade"></i> AO VIVO';
                    } else if (statusBadge && jogo.status_partida === 'Finalizado') {
                        statusBadge.innerHTML = '<span style="color:#10b981;"><i class="fa-solid fa-check"></i> FINALIZADO</span>';
                    }
                });
            }
        })
        .catch(error => console.error('Erro na Transmissão Live Score:', error));
    }, 3000);

    function atualizarRelogio() {
        const agora = new Date();
        const horas = String(agora.getHours()).padStart(2, '0');
        const minutos = String(agora.getMinutes()).padStart(2, '0');
        const segundos = String(agora.getSeconds()).padStart(2, '0');
        document.getElementById('relogioPainel').innerText = horas + ':' + minutos + ':' + segundos;
    }
    setInterval(atualizarRelogio, 1000);
    atualizarRelogio();

    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                console.log(`Erro: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>