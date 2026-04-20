<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$nivelAcesso = $this->data['nivelAcesso'] ?? 0;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos Gerais */
    .card-container { display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 30px; }
    .card-dash { background: #fff; padding: 20px; border-radius: 8px; flex: 1; min-width: 200px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border-left: 5px solid #0044cc; }
    .card-dash h3 { font-size: 0.9rem; color: #666; margin-bottom: 10px; }
    .card-dash p { font-size: 1.8rem; font-weight: bold; color: #0044cc; }
    
    /* Estilos Admin */
    .lider-box { background: linear-gradient(135deg, #0044cc 0%, #002266 100%); color: white; padding: 30px; border-radius: 12px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap; }
    .lider-img { width: 100px; height: 100px; border-radius: 50%; border: 4px solid #ffd700; object-fit: cover; }

    /* Estilos Árbitro & Atleta (Agenda e Histórico) */
    .arb-section-title { font-size: 16px; font-weight: bold; color: #333; margin: 20px 0 10px 0; border-left: 4px solid #ffc107; padding-left: 10px; }
    .arb-agenda-card { background: #fff; border-left: 5px solid #ffc107; border-radius: 8px; padding: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); margin-bottom: 15px; display: flex; flex-direction: column; gap: 10px; }
    .arb-torneio { font-size: 12px; color: #666; font-weight: bold; text-transform: uppercase; }
    .arb-vs { display: flex; align-items: center; justify-content: center; gap: 15px; font-size: 16px; font-weight: bold; color: #333; }
    .arb-vs .vs-badge { color: #dc3545; font-size: 14px; }
    .arb-footer { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #555; background: #f8f9fa; padding: 10px; border-radius: 4px; }
    .btn-apitar { background: #28a745; color: white; text-decoration: none; padding: 5px 15px; border-radius: 4px; font-weight: bold; }
    .arb-hist-item { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 12px; border-radius: 8px; margin-bottom: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-left: 4px solid #28a745; }
    .arb-hist-result { font-weight: bold; font-size: 16px; width: 60px; text-align: center; background: #333; color: white; border-radius: 4px; padding: 3px; }
    .arb-hist-details { flex-grow: 1; padding: 0 15px; }
    .arb-hist-details strong { display: block; color: #333; font-size: 14px; }
    .arb-hist-details small { color: #888; font-size: 11px; }

    /* Estilos da Vitrine do Atleta */
    .vitrine-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 15px; }
    .vitrine-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 6px 15px rgba(0,0,0,0.08); border-top: 5px solid #28a745; display: flex; flex-direction: column; transition: transform 0.2s; }
    .vitrine-card:hover { transform: translateY(-5px); }
    .vitrine-header { padding: 15px; background: #f8f9fa; border-bottom: 1px solid #eee; }
    .vitrine-body { padding: 15px; flex-grow: 1; }
    .vitrine-body p { margin: 8px 0; font-size: 14px; color: #444; }
    .vitrine-body i { color: #0044cc; width: 20px; text-align: center; margin-right: 5px; }
    .vitrine-footer { padding: 15px; text-align: center; border-top: 1px solid #eee; background: #fafafa; }
    .btn-inscrever { display: block; width: 100%; background: #28a745; color: white; text-decoration: none; padding: 12px; border-radius: 6px; font-weight: bold; text-transform: uppercase; transition: 0.3s; font-size: 14px; }
    .btn-inscrever:hover { background: #218838; }

    /* Badges de Pagamento para o Dashboard */
    .status-badge { position: absolute; top: 15px; right: 15px; display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .badge-ok { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.15); }
    .badge-warn { background-color: #ffc107; color: #333; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.15); }
    .badge-info { background-color: #17a2b8; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; box-shadow: 0 2px 4px rgba(0,0,0,0.15); }
</style>

<div class="dash-wrapper">
    <div class="row">
        
        <?php if ($nivelAcesso == 14): ?>
            <div class="top-list">
                <span class="title-content">🏆 Vitrine de Torneios</span>
            </div>
            <p style="color: #666; margin-top: -10px; margin-bottom: 20px;">Escolha a sua próxima competição e faça a sua inscrição!</p>

            <div class="content-adm-alert">
                <?php
                if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
                ?>
            </div>

            <div class="vitrine-grid">
                <?php if (!empty($this->data['vitrine'])): ?>
                    <?php foreach ($this->data['vitrine'] as $comp): 
                        $jaInscrito = $comp['ja_inscrito'] ?? false;
                        $statusPag = $comp['status_pagamento'] ?? 1;
                    ?>
                        <div class="vitrine-card" style="<?= $jaInscrito ? 'border-top-color: #28a745;' : '' ?>">
                            <div class="vitrine-header" style="position: relative; padding-right: 90px;">
                                <h3 style="margin: 0; color: #333; font-size: 18px; line-height: 1.2;"><?= $comp['nome_torneio'] ?></h3>
                                <small style="color: #888; font-weight: bold;"><i class="fa-solid fa-shield-halved" style="color: #666;"></i> Org: <?= $comp['clube_nome'] ?></small>
                                
                                <?php if ($jaInscrito): ?>
                                    <div class="status-badge">
                                        <span class="badge-ok"><i class="fa-solid fa-check"></i> INSCRITO</span>
                                        <?php if($statusPag == 1): ?>
                                            <span class="badge-warn"><i class="fa-solid fa-hourglass-half"></i> Aguarda Pgto</span>
                                        <?php elseif($statusPag == 2): ?>
                                            <span class="badge-ok"><i class="fa-solid fa-check-double"></i> Pago</span>
                                        <?php elseif($statusPag == 3): ?>
                                            <span class="badge-info"><i class="fa-solid fa-star"></i> Isento</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="vitrine-body">
                                <p><i class="fa-regular fa-calendar"></i> <strong>Data:</strong> <?= date('d/m/Y', strtotime($comp['data_evento'])) ?></p>
                                <p><i class="fa-solid fa-location-dot"></i> <strong>Local:</strong> <?= $comp['local_evento'] ?></p>
                                <p><i class="fa-solid fa-table-tennis-paddle-ball"></i> <strong>Categoria Base:</strong> <?= $comp['categoria_cbtm'] ?></p>
                            </div>
                            <div class="vitrine-footer">
                                <?php if ($jaInscrito): ?>
                                    <a href="<?= URLADM ?>inscricao-atleta/index/<?= $comp['id'] ?>" class="btn-inscrever" style="background: #17a2b8;"><i class="fa-solid fa-eye"></i> Ver Minha Inscrição</a>
                                <?php else: ?>
                                    <a href="<?= URLADM ?>inscricao-atleta/index/<?= $comp['id'] ?>" class="btn-inscrever">Quero me Inscrever!</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: #fff; padding: 40px; border-radius: 8px; grid-column: 1 / -1; text-align: center; color: #888; border: 2px dashed #ddd;">
                        <i class="fa-solid fa-calendar-xmark" style="font-size: 40px; color: #ccc; margin-bottom: 15px;"></i><br>
                        Nenhum torneio com inscrições abertas no momento na plataforma.<br>Fique de olho e prepare a sua raquete!
                    </div>
                <?php endif; ?>
            </div>

            <h3 class="arb-section-title" style="border-left-color: #0044cc; margin-top: 40px;">⌚ Meus Próximos Jogos</h3>
            <div style="margin-bottom: 20px;">
                <?php if (!empty($this->data['stats']['atleta_proximos'])): ?>
                    <?php foreach ($this->data['stats']['atleta_proximos'] as $prox): 
                        $meuId = $_SESSION['user_id'];
                        $souA = ($prox['atleta_a_id'] == $meuId);
                    ?>
                        <div class="arb-agenda-card" style="border-left-color: #0044cc;">
                            <span class="arb-torneio"><?= $prox['nome_torneio'] ?> - <?= $prox['fase'] ?></span>
                            
                            <div class="arb-vs">
                                <span <?= $souA ? 'style="color:#0044cc;"' : '' ?>><?= $prox['atleta_a'] ?></span>
                                <span class="vs-badge">VS</span>
                                <span <?= !$souA ? 'style="color:#0044cc;"' : '' ?>><?= $prox['atleta_b'] ?></span>
                            </div>
                            
                            <div class="arb-footer">
                                <div>
                                    <span style="font-size: 14px; font-weight: bold; color: #0044cc; margin-right: 10px;">🏓 Mesa <?= $prox['mesa'] ?></span>
                                    <span>⌚ <?= !empty($prox['horario_previsto']) ? date('H:i', strtotime($prox['horario_previsto'])) : 'A definir' ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px; border: 1px dashed #ccc;">
                        Você não tem jogos agendados no momento.
                    </div>
                <?php endif; ?>
            </div>

            <h3 class="arb-section-title" style="border-left-color: #28a745; margin-top: 20px;">📜 Meu Histórico de Partidas</h3>
            <div>
                <?php if (!empty($this->data['stats']['atleta_historico'])): ?>
                    <?php foreach ($this->data['stats']['atleta_historico'] as $hist): 
                        $venci = ($hist['vencedor_id'] == $_SESSION['user_id']);
                        $corBorda = $venci ? '#28a745' : '#dc3545';
                    ?>
                        <div class="arb-hist-item" style="border-left-color: <?= $corBorda ?>;">
                            <?php if($hist['is_wo'] == 1): ?>
                                <div class="arb-hist-result" style="background: <?= $corBorda ?>; font-size: 12px; padding: 5px;">W.O.</div>
                            <?php else: ?>
                                <div class="arb-hist-result" style="background: <?= $corBorda ?>;"><?= $hist['sets_atleta_a'] ?> x <?= $hist['sets_atleta_b'] ?></div>
                            <?php endif; ?>
                            
                            <div class="arb-hist-details">
                                <strong><?= $hist['atleta_a'] ?> vs <?= $hist['atleta_b'] ?></strong>
                                <small><?= $hist['nome_torneio'] ?> (<?= $hist['fase'] ?>)</small>
                            </div>
                            <div>
                                <?php if($venci): ?>
                                    <span style="color: #28a745; font-weight: bold; font-size: 12px;"><i class="fa-solid fa-trophy"></i> Vitória</span>
                                <?php else: ?>
                                    <span style="color: #dc3545; font-weight: bold; font-size: 12px;"><i class="fa-solid fa-xmark"></i> Derrota</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px; border: 1px dashed #ccc;">
                        Você ainda não disputou nenhuma partida na plataforma.
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($nivelAcesso == 15): ?>
            <div class="top-list">
                <span class="title-content">Escala de Arbitragem</span>
            </div>

            <div class="card-container" style="margin-bottom: 15px;">
                <div class="card-dash" style="border-left-color: #ffc107;">
                    <h3>Histórico Profissional</h3>
                    <p style="color: #333;"><?= $this->data['stats']['arbitro_stats']['total_apitos'] ?? 0 ?> <span style="font-size: 14px; color: #666;">jogos apitados</span></p>
                </div>
            </div>

            <h3 class="arb-section-title">⌚ Próximos Jogos (Escalado)</h3>
            <div>
                <?php if (!empty($this->data['stats']['arbitro_proximos'])): ?>
                    <?php foreach ($this->data['stats']['arbitro_proximos'] as $prox): ?>
                        <div class="arb-agenda-card">
                            <span class="arb-torneio"><?= $prox['nome_torneio'] ?> - <?= $prox['fase'] ?></span>
                            
                            <div class="arb-vs">
                                <span><?= $prox['atleta_a'] ?></span>
                                <span class="vs-badge">VS</span>
                                <span><?= $prox['atleta_b'] ?></span>
                            </div>
                            
                            <div class="arb-footer">
                                <div>
                                    <span style="font-size: 14px; font-weight: bold; color: #0044cc; margin-right: 10px;">🏓 Mesa <?= $prox['mesa'] ?></span>
                                    <span>⌚ <?= !empty($prox['horario_previsto']) ? date('H:i', strtotime($prox['horario_previsto'])) : 'A definir' ?></span>
                                </div>
                                <a href="<?= URLADM ?>edit-partida/index/<?= $prox['id'] ?>" class="btn-apitar">Lançar Súmula</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px;">
                        Você não está escalado para nenhuma mesa no momento.
                    </div>
                <?php endif; ?>
            </div>

            <h3 class="arb-section-title">📜 Últimos Jogos Apitados</h3>
            <div>
                <?php if (!empty($this->data['stats']['arbitro_historico'])): ?>
                    <?php foreach ($this->data['stats']['arbitro_historico'] as $hist): ?>
                        <div class="arb-hist-item">
                            <?php if($hist['is_wo'] == 1): ?>
                                <div class="arb-hist-result" style="background: #dc3545; font-size: 12px; padding: 5px;">W.O.</div>
                            <?php else: ?>
                                <div class="arb-hist-result"><?= $hist['sets_atleta_a'] ?> x <?= $hist['sets_atleta_b'] ?></div>
                            <?php endif; ?>
                            
                            <div class="arb-hist-details">
                                <strong><?= $hist['atleta_a'] ?> vs <?= $hist['atleta_b'] ?></strong>
                                <small><?= $hist['nome_torneio'] ?> (<?= $hist['fase'] ?>)</small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px;">
                        Nenhum jogo finalizado no seu histórico.
                    </div>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <div class="top-list">
                <span class="title-content">📊 Painel da Organização - TMNet</span>
            </div>

            <div class="card-container">
                <div class="card-dash">
                    <h3>Total de Atletas Ativos</h3>
                    <p><?= $this->data['stats']['contagem']['atletas'] ?? 0 ?></p>
                </div>
                <div class="card-dash" style="border-left-color: #28a745;">
                    <h3>Competições Criadas</h3>
                    <p style="color: #28a745;"><?= $this->data['stats']['contagem']['competicoes'] ?? 0 ?></p>
                </div>
                <div class="card-dash" style="border-left-color: #dc3545;">
                    <h3>Jogos Finalizados</h3>
                    <p style="color: #dc3545;"><?= $this->data['stats']['contagem']['partidas'] ?? 0 ?></p>
                </div>
            </div>

            <?php if (!empty($this->data['stats']['lider'])): ?>
            <div class="lider-box">
                <?php 
                    $lider = $this->data['stats']['lider'];
                    $foto = (!empty($lider['imagem']) && file_exists("app/adms/assets/image/users/"  .$lider['id'] ."/" . $lider['imagem'])) 
                        ? URLADM . "app/adms/assets/image/users/" .$lider['id'] ."/" . $lider['imagem'] 
                        : URLADM . "app/adms/assets/image/users/icon_user.png";
                ?>
                <img src="<?= $foto ?>" class="lider-img">
                <div>
                    <h2 style="margin: 0; color: #ffd700;">👑 Top #1 do Ranking</h2>
                    <p style="font-size: 1.5rem; margin: 5px 0; font-weight: bold;">
                        <a href="<?= URLADM ?>perfil-atleta/index/<?= $lider['id'] ?>" style="color: white; text-decoration: none;">
                            <?= $lider['nome'] ?> (<?= $lider['apelido'] ?>)
                        </a>
                    </p>
                    <span style="background: rgba(255,215,0,0.3); padding: 5px 15px; border-radius: 20px; font-weight: bold;">
                        <?= $lider['pontuacao_ranking'] ?> Pontos Atuais
                    </span>
                </div>
            </div>
            <?php else: ?>
                <div style="background: #fff; padding: 20px; border-radius: 8px; border: 1px dashed #ccc; text-align: center;">
                    <p style="color: #666;">Ainda não existem atletas com pontuação no ranking para exibir o líder.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
    </div>
</div>