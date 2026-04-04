<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<style>
    /* Design Responsivo e Abas */
    .tabs-header { display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px; }
    .tab-btn { background: #e9ecef; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; font-weight: bold; color: #555; white-space: nowrap; transition: 0.3s; }
    .tab-btn.active { background: #0044cc; color: white; box-shadow: 0 4px 6px rgba(0,68,204,0.3); }
    
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* Estilos dos Cards dos Atletas */
    .ranking-grid { display: flex; flex-direction: column; gap: 10px; }
    .atleta-card { display: flex; align-items: center; background: #fff; border-radius: 8px; padding: 10px 15px; box-shadow: 0 1px 4px rgba(0,0,0,0.1); border-left: 4px solid transparent; }
    .atleta-card:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
    
    .pos-box { width: 40px; font-size: 18px; font-weight: bold; text-align: center; color: #666; }
    .pos-1 { border-left-color: #ffd700; } .pos-1 .pos-box { color: #d4af37; font-size: 22px; }
    .pos-2 { border-left-color: #c0c0c0; } .pos-2 .pos-box { color: #8e8d8d; font-size: 20px; }
    .pos-3 { border-left-color: #cd7f32; } .pos-3 .pos-box { color: #b87333; font-size: 20px; }
    
    .img-box { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; border: 2px solid #eee; margin: 0 15px; }
    .info-box { flex-grow: 1; }
    .info-box strong { font-size: 16px; color: #333; display: block; }
    .info-box small { color: #777; font-size: 12px; display: block; }
    
    .pontos-box { background: #0044cc; color: white; padding: 5px 12px; border-radius: 15px; font-weight: bold; font-size: 14px; white-space: nowrap; }

    /* Filtros de Gênero na Divisão */
    .gen-filters { display: flex; gap: 5px; margin-top: 10px; margin-bottom: 15px; }
    .gen-btn { font-size: 12px; padding: 4px 10px; border-radius: 12px; border: 1px solid #ccc; background: #fff; cursor: pointer; }
    .gen-btn.active-gen { background: #333; color: #fff; border-color: #333; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list" style="flex-wrap: wrap; gap: 15px;">
            <span class="title-content">🏆 Ranking Oficial TMNet</span>
            
            <div class="top-list-right" style="width: 100%; max-width: 300px;">
                <form method="POST" action="" style="display: flex; gap: 5px; margin: 0; width: 100%;">
                    <input type="text" name="search_nome" class="input-adm" placeholder="Pesquisar atleta..." 
                           value="<?= $this->data['form']['search_nome'] ?? '' ?>" 
                           style="margin: 0; flex-grow: 1; border-radius: 20px; padding: 8px 15px;">
                    <button type="submit" class="btn-info" style="margin: 0; border-radius: 20px; padding: 8px 15px;">🔍</button>
                </form>
            </div>
        </div>

        <div class="tabs-header">
            <button class="tab-btn active" onclick="openTab('tab-geral')">Ranking Geral</button>
            <?php foreach ($this->data['ranking_divisao'] as $divId => $divData): ?>
                <button class="tab-btn" onclick="openTab('tab-div-<?= $divId ?>')"><?= $divData['nome_divisao'] ?></button>
            <?php endforeach; ?>
        </div>

        <div id="tab-geral" class="tab-content active">
            <div class="ranking-grid">
                <?php 
                if (!empty($this->data['ranking_geral'])) {
                    renderCardsRanking($this->data['ranking_geral'], $this->data['form']);
                } else {
                    echo "<p style='text-align: center; color: #666; padding: 20px;'>Nenhum atleta encontrado.</p>";
                }
                ?>
            </div>
        </div>

        <?php foreach ($this->data['ranking_divisao'] as $divId => $divData): ?>
            <div id="tab-div-<?= $divId ?>" class="tab-content">
                
                <div class="gen-filters">
                    <button class="gen-btn active-gen" onclick="filterGen('<?= $divId ?>', 'Todos')">Misto (Geral)</button>
                    <?php if(isset($divData['generos']['Masculino'])): ?>
                        <button class="gen-btn" onclick="filterGen('<?= $divId ?>', 'Masculino')">Masculino</button>
                    <?php endif; ?>
                    <?php if(isset($divData['generos']['Feminino'])): ?>
                        <button class="gen-btn" onclick="filterGen('<?= $divId ?>', 'Feminino')">Feminino</button>
                    <?php endif; ?>
                </div>

                <div class="ranking-grid" id="grid-<?= $divId ?>-Todos">
                    <?php renderCardsRanking($divData['geral'], $this->data['form']); ?>
                </div>
                
                <?php if(isset($divData['generos']['Masculino'])): ?>
                    <div class="ranking-grid" id="grid-<?= $divId ?>-Masculino" style="display: none;">
                        <?php renderCardsRanking($divData['generos']['Masculino'], $this->data['form']); ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($divData['generos']['Feminino'])): ?>
                    <div class="ranking-grid" id="grid-<?= $divId ?>-Feminino" style="display: none;">
                        <?php renderCardsRanking($divData['generos']['Feminino'], $this->data['form']); ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php
// Função PHP auxiliar para não repetir o código HTML do Card várias vezes
function renderCardsRanking($listaAtletas, $formSearch) {
    $posicao = 1;
    
    // Captura os dados do usuário que está logado
    $userIdLogado = $_SESSION['user_id'] ?? 0;
    $nivelLogado = $_SESSION['adms_access_level_id'] ?? 0;

    foreach ($listaAtletas as $atleta) {
        $classePosicao = "";
        $medalha = $posicao . "º";
        
        if(empty($formSearch['search_nome'])) {
            if($posicao == 1) { $classePosicao = "pos-1"; $medalha = "🥇"; }
            elseif($posicao == 2) { $classePosicao = "pos-2"; $medalha = "🥈"; }
            elseif($posicao == 3) { $classePosicao = "pos-3"; $medalha = "🥉"; }
        }
        
        $foto = (!empty($atleta['imagem']) && file_exists("app/adms/assets/image/users/" . $atleta['imagem'])) 
                ? URLADM . "app/adms/assets/image/users/" . $atleta['imagem'] 
                : URLADM . "app/adms/assets/image/users/icone_usuario.png";
        
        $iconeGen = ($atleta['genero'] == 'F') ? '👩' : '👨';

        // =========================================================
        // TRAVA DE PRIVACIDADE NA VIEW
        // Permite clicar APENAS se for Admin/Organizador (Nível != 14) 
        // OU se for o próprio atleta a clicar no seu nome
        // =========================================================
        $podeClicar = ($nivelLogado != 14 || $userIdLogado == $atleta['id']);

        echo "<div class='atleta-card {$classePosicao}'>
                <div class='pos-box'>{$medalha}</div>
                <img src='{$foto}' class='img-box'>
                <div class='info-box'>";
        
        if ($podeClicar) {
            echo "<a href='" . URLADM . "perfil-atleta/index/{$atleta['id']}' style='text-decoration: none;'>";
        } else {
            echo "<div style='color: inherit;'>"; // Mantém a estética mas sem ser clicável
        }

        echo "      <strong>{$atleta['nome']} {$iconeGen}</strong>
                    <small>{$atleta['apelido']} | Estilo: " . ($atleta['estilo_jogo'] ?? 'N/A') . "</small>";
        
        if ($podeClicar) {
            echo "</a>";
        } else {
            echo "</div>";
        }

        echo "  </div>
                <div class='pontos-box'>{$atleta['pontuacao_ranking']} pts</div>
              </div>";
        
        $posicao++;
    }
}
?>

<script>
// JS para Alternar as Abas Principais (Divisões)
function openTab(tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].classList.remove("active");
    }
    tablinks = document.getElementsByClassName("tab-btn");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }
    document.getElementById(tabName).classList.add("active");
    event.currentTarget.classList.add("active");
}

// JS para Alternar o Gênero dentro de uma Divisão
function filterGen(divId, genero) {
    var container = document.getElementById('tab-div-' + divId);
    
    // Esconde todas as grids desta divisão
    var grids = container.getElementsByClassName('ranking-grid');
    for (var i = 0; i < grids.length; i++) {
        grids[i].style.display = 'none';
    }
    
    // Mostra apenas a selecionada
    document.getElementById('grid-' + divId + '-' + genero).style.display = 'flex';
    
    // Atualiza o botão ativo
    var btns = container.getElementsByClassName('gen-btn');
    for (var i = 0; i < btns.length; i++) {
        btns[i].classList.remove('active-gen');
    }
    event.currentTarget.classList.add('active-gen');
}
</script>