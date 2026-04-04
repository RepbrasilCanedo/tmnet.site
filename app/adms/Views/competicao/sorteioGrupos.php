<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<style>
    .grupo-card { background: #fff; border: 1px solid #ddd; border-top: 4px solid #0044cc; border-radius: 5px; padding: 15px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .grupo-card h3 { margin-top: 0; color: #0044cc; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    .grid-grupos { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 20px; }
    .divisao-header { background: #17a2b8; color: white; padding: 5px 10px; margin-top: 10px; margin-bottom: 5px; border-radius: 3px; font-weight: bold; font-size: 14px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Sorteio e Chaveamento</span>
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

        <div class="content-adm">
            <form method="POST" action="" class="form-adm">
                <div class="row-input">
                    <div class="column" style="flex: 0 0 300px;">
                        
                        <?php if (isset($this->data['tipo_competicao']) && $this->data['tipo_competicao'] == 1): ?>
                            <div style="background: #e9ecef; padding: 10px; border-radius: 4px; border-left: 5px solid #6c757d; margin-bottom: 15px; font-size: 13px;">
                                <strong>🎲 Torneio Livre / Amador</strong><br>O algoritmo embaralhará os atletas aleatoriamente dentro de cada categoria, ignorando o ranking.
                            </div>
                        <?php else: ?>
                            <div style="background: #e9ecef; padding: 10px; border-radius: 4px; border-left: 5px solid #17a2b8; margin-bottom: 15px; font-size: 13px;">
                                <strong>🏆 Sorteio Profissional (Snake)</strong><br>As chaves serão geradas baseadas no ranking dos atletas para evitar que os melhores se enfrentem nos grupos.
                            </div>
                        <?php endif; ?>

                        <?php if (isset($this->data['sistema_disputa']) && $this->data['sistema_disputa'] == 1): ?>
                            <label class="title-input">Quantidade de Grupos (por Categoria)</label>
                            <select name="qtd_grupos" class="input-adm" required>
                                <option value="2">2 Grupos</option>
                                <option value="4" selected>4 Grupos</option>
                                <option value="8">8 Grupos</option>
                                <option value="16">16 Grupos</option>
                            </select>
                            <?php $textoBotao = "⚙️ Gerar Chaveamento (Snake)"; ?>
                        <?php else: ?>
                            <input type="hidden" name="qtd_grupos" value="1">
                            <?php $textoBotao = "⚙️ Gerar Partidas (Todos x Todos)"; ?>
                        <?php endif; ?>
                        
                        <?php if (isset($this->data['status_inscricao']) && $this->data['status_inscricao'] == 1): ?>
                            <div style="background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; margin-top: 15px; border-left: 4px solid #ffc107; font-size: 13px;">
                                <strong>Atenção:</strong> Inscrições abertas! Encerre na súmula para liberar o sorteio.
                            </div>
                            <button type="button" class="btn-success" style="background-color: #ccc; width: 100%; margin-top: 10px; cursor: not-allowed; border: none; padding: 10px; border-radius: 4px; color: #666; font-weight: bold;" disabled>
                                🔒 Geração Bloqueada
                            </button>
                        <?php else: ?>
                            <button type="submit" name="AdmsGerarSorteio" class="btn-success" value="Gerar" style="background-color: #0044cc; width: 100%; margin-top: 15px; cursor: pointer; border: none; padding: 10px; border-radius: 4px; color: white; font-weight: bold;" onclick="return confirm('Isso apagará qualquer sorteio anterior e gerará novos grupos. Tem a certeza?');">
                                <?= $textoBotao ?>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="column" style="border-left: 1px solid #eee; padding-left: 20px;">
                        <label class="title-input">Atletas Inscritos (Separados por Categoria)</label>
                        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ccc; padding: 10px; border-radius: 4px; background: #fafafa;">
                            
                            <?php 
                            if (!empty($this->data['atletas'])) {
                                $categoriaAtual = null;
                                foreach ($this->data['atletas'] as $atleta) {
                                    if ($categoriaAtual !== $atleta['cat_nome']) {
                                        $categoriaAtual = $atleta['cat_nome'];
                                        $nomeDisplay = $categoriaAtual ? $categoriaAtual : "Categoria Indefinida";
                                        echo "<div class='divisao-header'>🏆 {$nomeDisplay}</div>";
                                    }
                                    
                                    echo "<div style='margin-bottom: 3px; background: #fff; padding: 4px; border: 1px solid #eee; margin-left: 10px;'>
                                            <input type='checkbox' name='inscricoes_ids[]' value='{$atleta['inscricao_id']}' id='insc_{$atleta['inscricao_id']}' checked>
                                            <label for='insc_{$atleta['inscricao_id']}' style='cursor: pointer;'>
                                                {$atleta['name']} <small style='color: #666;'>({$atleta['pontuacao_ranking']} pts)</small>
                                            </label>
                                          </div>";
                                }
                            } else {
                                echo "<p style='color: #666;'>Nenhum atleta inscrito ainda.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php if (!empty($this->data['grupos_gerados'])): ?>
            <h2 style="margin-top: 30px; color: #333;">Resultados do Chaveamento</h2>
            <div class="grid-grupos">
                <?php 
                $gruposOrganizados = [];
                
                // Organiza tudo por: Categoria -> Gênero -> Grupo
                foreach ($this->data['grupos_gerados'] as $g) {
                    $catNome = $g['cat_nome'] ?? 'Categoria Desconhecida';
                    
                    $genLabel = "Misto";
                    if ($g['tipo_genero'] == 2) {
                        $genLabel = ($g['genero'] == 'F') ? 'Feminino' : 'Masculino';
                    }
                    
                    // Limpa o prefixo visualmente
                    $nomeGrupo = str_replace(['M-', 'F-'], '', $g['grupo']);
                    
                    $gruposOrganizados[$catNome][$genLabel][$nomeGrupo][] = $g;
                }
                
                foreach ($gruposOrganizados as $nomeCategoria => $generos) {
                    echo "<div style='grid-column: 1 / -1;'><h3 style='background: #333; color: white; padding: 10px; border-radius: 4px; margin-bottom: 0;'>🏆 {$nomeCategoria}</h3></div>";
                    
                    foreach ($generos as $nomeGenero => $grupos) {
                        $corGen = ($nomeGenero == 'Feminino') ? '#e83e8c' : '#0044cc';
                        if ($nomeGenero != 'Misto') {
                            echo "<div style='grid-column: 1 / -1;'><h4 style='color: {$corGen}; margin: 5px 0 0 0; border-bottom: 2px solid {$corGen}; display: inline-block;'>{$nomeGenero}</h4></div>";
                        }
                        
                        foreach ($grupos as $nomeGrupo => $integrantes) {
                            echo "<div class='grupo-card' style='margin-top: 5px; border-top-color: {$corGen};'><h3>Grupo {$nomeGrupo}</h3><ol style='padding-left: 20px; margin: 0;'>";
                            foreach ($integrantes as $int) {
                                $icone = ($int['genero'] == 'F') ? '👩' : '👨';
                                echo "<li style='margin-bottom: 5px;'>{$icone} {$int['name']} <small style='color: #666;'>({$int['pontuacao_ranking']} pts)</small></li>";
                            }
                            echo "</ol></div>";
                        }
                    }
                }
                ?>
            </div>
        <?php endif; ?>

    </div>
</div>