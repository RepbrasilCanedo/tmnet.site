<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$perfil = $this->data['perfil'];
$stats = $this->data['estatisticas'];
$idAtletaLogado = $perfil['id'];

// Define a foto de perfil
$foto = (!empty($perfil['imagem']) && file_exists("app/adms/assets/image/users/" . $perfil['id'] ."/".  $perfil['imagem'] )) 
        ? URLADM . "app/adms/assets/image/users/" . $perfil['id'] ."/".  $perfil['imagem'] 
        : URLADM . "app/adms/assets/image/users/icone_usuario.png";
?>
<style>
    /* Design Mobile-First App Style */
    .perfil-app-container { max-width: 600px; margin: 0 auto; background: #f4f6f9; min-height: 100vh; padding-bottom: 30px; }
    
    /* Header do Jogador */
    .perfil-header { background: linear-gradient(135deg, #0044cc 0%, #002266 100%); color: white; text-align: center; padding: 40px 20px 60px; border-radius: 0 0 30px 30px; position: relative; margin-bottom: 50px; }
    .perfil-foto-box { position: absolute; bottom: -45px; left: 50%; transform: translateX(-50%); width: 100px; height: 100px; border-radius: 50%; border: 4px solid #fff; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); overflow: hidden; }
    .perfil-foto-box img { width: 100%; height: 100%; object-fit: cover; }
    .perfil-nome { font-size: 22px; font-weight: bold; margin: 0; }
    .perfil-nick { font-size: 14px; color: #b3c6ff; margin-bottom: 10px; display: block; }
    
    /* Badges / Informações Rápidas */
    .badges-row { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-top: 15px; }
    .badge-info { background: rgba(255,255,255,0.2); padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
    
    /* Estatísticas Cards */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; padding: 0 15px; margin-bottom: 25px; }
    .stat-card { background: #fff; border-radius: 12px; padding: 15px 5px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .stat-num { font-size: 20px; font-weight: bold; color: #333; display: block; margin-bottom: 5px; }
    .stat-label { font-size: 10px; color: #888; text-transform: uppercase; font-weight: bold; }
    
    /* Seções (Próximos Jogos & Histórico) */
    .section-title { padding: 0 15px; font-size: 16px; font-weight: bold; color: #333; margin-bottom: 10px; border-left: 4px solid #0044cc; margin-left: 15px; }
    
    /* Card Próximo Jogo */
    .agenda-list { padding: 0 15px; margin-bottom: 25px; display: flex; flex-direction: column; gap: 12px; }
    .agenda-card { background: #fff; border-left: 5px solid #ffc107; border-radius: 8px; padding: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); position: relative; overflow: hidden; }
    .agenda-torneio { font-size: 12px; color: #666; font-weight: bold; margin-bottom: 8px; display: block; text-transform: uppercase; }
    .agenda-vs { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
    .agenda-vs span { font-size: 16px; font-weight: bold; color: #333; flex: 1; }
    .agenda-vs .vs-badge { color: #dc3545; font-size: 14px; text-align: center; flex: 0.2; }
    .agenda-footer { display: flex; gap: 15px; font-size: 12px; color: #555; background: #f8f9fa; padding: 8px; border-radius: 4px; }
    
    /* Lista de Histórico */
    .hist-list { padding: 0 15px; display: flex; flex-direction: column; gap: 10px; }
    .hist-item { display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 12px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .hist-win { border-left: 4px solid #28a745; }
    .hist-loss { border-left: 4px solid #dc3545; }
    .hist-result { font-weight: bold; font-size: 16px; width: 45px; text-align: center; }
    .hist-details { flex-grow: 1; padding: 0 10px; }
    .hist-details strong { display: block; color: #333; font-size: 14px; }
    .hist-details small { color: #888; font-size: 11px; }
</style>

<div class="dash-wrapper" style="padding: 0;">
    <div class="perfil-app-container">
        
        <div class="perfil-header">
            <h1 class="perfil-nome"><?= $perfil['name'] ?></h1>
            <span class="perfil-nick">@<?= $perfil['apelido'] ?></span>
            
            <div class="badges-row">
                <span class="badge-info">🏆 <?= $perfil['pontuacao_ranking'] ?> pts</span>
                <span class="badge-info">🏓 <?= $perfil['estilo_jogo'] ?? 'Clássico' ?></span>
                <span class="badge-info">✋ <?= $perfil['mao_dominante'] ?? 'Destro' ?></span>
            </div>
            
            <div class="perfil-foto-box">
                <img src="<?= $foto ?>" alt="Foto do Atleta">
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-num"><?= $stats['total_jogos'] ?></span>
                <span class="stat-label">Jogos</span>
            </div>
            <div class="stat-card">
                <span class="stat-num" style="color: #28a745;"><?= $stats['vitorias'] ?></span>
                <span class="stat-label">Vitórias</span>
            </div>
            <div class="stat-card">
                <span class="stat-num" style="color: #dc3545;"><?= $stats['derrotas'] ?></span>
                <span class="stat-label">Derrotas</span>
            </div>
            <div class="stat-card">
                <span class="stat-num" style="color: #0044cc;"><?= $stats['aproveitamento'] ?>%</span>
                <span class="stat-label">Win Rate</span>
            </div>
        </div>

        <h3 class="section-title">🚀 Próximos Jogos</h3>
        <div class="agenda-list">
            <?php if (!empty($this->data['proximos_jogos'])): ?>
                <?php foreach ($this->data['proximos_jogos'] as $prox): 
                    // Identifica quem é o adversário
                    $adversario = ($idAtletaLogado == $prox['atleta_a_id']) ? $prox['nome_b'] : $prox['nome_a'];
                ?>
                    <div class="agenda-card">
                        <span class="agenda-torneio"><?= $prox['nome_torneio'] ?> - <?= $prox['fase'] ?></span>
                        
                        <div class="agenda-vs">
                            <span style="text-align: right;">Você</span>
                            <span class="vs-badge">VS</span>
                            <span style="text-align: left;"><?= $adversario ?></span>
                        </div>
                        
                        <div class="agenda-footer">
                            <div>📍 Mesa <?= $prox['mesa'] ?? '?' ?></div>
                            <div>⌚ <?= !empty($prox['horario_previsto']) ? date('H:i', strtotime($prox['horario_previsto'])) : 'A definir' ?></div>
                            <div>📅 <?= date('d/m', strtotime($prox['data_evento'])) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px;">
                    Sem jogos agendados no momento.
                </div>
            <?php endif; ?>
        </div>

        <h3 class="section-title">📜 Últimas Partidas</h3>
        <div class="hist-list">
            <?php if (!empty($this->data['historico'])): ?>
                <?php foreach ($this->data['historico'] as $hist): 
                    $venceu = ($hist['vencedor_id'] == $idAtletaLogado);
                    $classe = $venceu ? 'hist-win' : 'hist-loss';
                    $adversario = ($idAtletaLogado == $hist['atleta_a_id']) ? $hist['nome_b'] : $hist['nome_a'];
                    
                    // Ordena os sets para mostrar primeiro os do atleta logado
                    if ($idAtletaLogado == $hist['atleta_a_id']) {
                        $placar = "{$hist['sets_atleta_a']} x {$hist['sets_atleta_b']}";
                    } else {
                        $placar = "{$hist['sets_atleta_b']} x {$hist['sets_atleta_a']}";
                    }
                ?>
                    <div class="hist-item <?= $classe ?>">
                        <div class="hist-result" style="color: <?= $venceu ? '#28a745' : '#dc3545' ?>;">
                            <?= $placar ?>
                        </div>
                        <div class="hist-details">
                            <strong>vs <?= $adversario ?></strong>
                            <small><?= $hist['nome_torneio'] ?> (<?= $hist['fase'] ?>)</small>
                        </div>
                        <div style="font-size: 12px; font-weight: bold; color: <?= $venceu ? '#0044cc' : '#999' ?>;">
                            <?= $venceu ? '+'.$hist['pontos_ganhos'].' pts' : '0 pts' ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="background: #fff; padding: 20px; text-align: center; border-radius: 8px; color: #888; font-size: 14px;">
                    Nenhum jogo finalizado na sua carreira.
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>