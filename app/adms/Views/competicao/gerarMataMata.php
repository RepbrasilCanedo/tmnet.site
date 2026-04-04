<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<style>
    .grupo-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .grupo-table th { background: #0044cc; color: #fff; padding: 8px; text-align: left; }
    .grupo-table td { padding: 8px; border-bottom: 1px solid #ddd; background: #fff; }
    .classificado { border-left: 4px solid #28a745; background-color: #f8fff9 !important; font-weight: bold; }
    .eliminado { color: #666; }
    .grid-classificacao { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 10px; margin-bottom: 30px; }
    .divisao-title { background: #333; color: #fff; padding: 10px 15px; border-radius: 4px; font-size: 18px; font-weight: bold; margin-top: 20px; }
    .genero-title { font-size: 16px; margin-top: 15px; border-bottom: 2px solid; display: inline-block; padding-bottom: 3px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Classificação e Eliminatórias</span>
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

        <div class="content-adm" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
            <p style="margin-top: 0;"><strong>Atenção:</strong> Certifique-se de que todos os jogos da fase de grupos terminaram. O sistema extrairá os <strong>2 melhores</strong> de cada grupo, em cada categoria e gênero, e criará o cruzamento.</p>
            
            <form method="POST" action="" style="margin-top: 15px;">
                <button type="submit" name="AdmsGerarMataMata" class="btn-success" value="Gerar" style="background-color: #28a745; font-size: 16px; height: 40px; padding: 0 20px; border-radius: 4px; border: none; color: white; cursor: pointer;">
                    🏆 Extrair Classificados e Gerar Mata-Mata
                </button>
            </form>
        </div>

        <?php if (!empty($this->data['classificacao'])): ?>
            
            <?php foreach ($this->data['classificacao'] as $catId => $generos): ?>
                
                <?php 
                // Pega o nome da categoria do primeiro item para exibir no bloco
                $nomeCategoriaBloco = reset($generos)['nome_categoria'];
                ?>
                <div class="divisao-title">🏆 <?= $nomeCategoriaBloco ?></div>
                
                <?php foreach ($generos as $genId => $genData): 
                    $corGen = ($genId == 'F') ? '#e83e8c' : '#0044cc';
                ?>
                    
                    <?php if ($genData['nome_genero'] != 'Misto'): ?>
                        <div class="genero-title" style="color: <?= $corGen ?>; border-color: <?= $corGen ?>;">
                            <?= $genData['nome_genero'] ?>
                        </div>
                    <?php endif; ?>

                    <div class="grid-classificacao">
                        <?php foreach ($genData['grupos'] as $grupo => $atletas): ?>
                            <table class="grupo-table">
                                <thead>
                                    <tr>
                                        <th colspan="3" style="background-color: <?= ($genData['nome_genero'] != 'Misto') ? $corGen : '#0044cc' ?>;">
                                            Grupo <?= $grupo ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="width: 40px; text-align: center; background-color: #f4f4f4; color: #333;">Pos</th>
                                        <th style="background-color: #f4f4f4; color: #333;">Atleta</th>
                                        <th style="width: 50px; text-align: center; background-color: #f4f4f4; color: #333;">Vit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $pos = 1;
                                    foreach ($atletas as $atleta): 
                                        $classeRow = ($pos <= 2) ? 'classificado' : 'eliminado';
                                        $selo = ($pos <= 2) ? '⭐' : '';
                                    ?>
                                        <tr>
                                            <td class="<?= $classeRow ?>" style="text-align: center;"><?= $pos ?>º</td>
                                            <td class="<?= $classeRow ?>"><?= $atleta['nome'] ?> <?= $selo ?></td>
                                            <td class="<?= $classeRow ?>" style="text-align: center;"><?= $atleta['vitorias'] ?></td>
                                        </tr>
                                    <?php 
                                    $pos++;
                                    endforeach; 
                                    ?>
                                </tbody>
                            </table>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

            <?php endforeach; ?>
            
        <?php else: ?>
            <p style="color: red; text-align: center;">Nenhum grupo ou jogo finalizado encontrado para esta competição.</p>
        <?php endif; ?>

    </div>
</div>