<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<style>
    .mesa-card { background: #fff; border: 1px solid #ccc; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); overflow: hidden; }
    .mesa-header { background: #0044cc; color: white; padding: 8px 15px; font-size: 16px; font-weight: bold; display: flex; justify-content: space-between; align-items: center; }
    .mesa-jogo { padding: 10px 15px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .mesa-jogo:last-child { border-bottom: none; }
    .atleta-nome { font-weight: bold; font-size: 14px; }
    .vs { color: #dc3545; font-weight: bold; margin: 0 10px; }
    .btn-lancar { background: #28a745; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none; font-size: 12px; }
    .grid-mesas { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 20px; margin-top: 20px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Distribuição de Mesas e Jogos</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['competicao_id'] ?>" class="btn-info">Voltar à Súmula</a>
                <a href="<?= URLADM ?>gerar-fichas-pdf/index/<?= $this->data['competicao_id'] ?>" target="_blank" class="btn-info" style="background-color: #17a2b8; color: white;">🖨️ Imprimir Fichas de Mesa</a>
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

        <div class="content-adm" style="background: #f8f9fa; border-left: 4px solid #0044cc; padding: 15px;">
            <form method="POST" action="" style="display: flex; gap: 15px; align-items: flex-end;">
                <input type="hidden" name="AdmsGerar" value="Gerar">
                
                <div>
                    <label class="title-input">Quantidade de Mesas Disponíveis</label>
                    <input type="number" name="qtd_mesas" class="input-adm" min="1" value="8" style="width: 150px;" required>
                </div>
                <button type="submit" class="btn-warning" style="background-color: #0044cc; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer;">Gerar / Atualizar Agenda</button>
            </form>
            <small style="color: #666; display: block; margin-top: 10px;">*Atenção: A geração da agenda irá distribuir todos os jogos pendentes pelas mesas informadas.</small>
        </div>

        <?php if (!empty($this->data['agenda_jogos'])): ?>
            <div class="grid-mesas">
                <?php 
                $jogosPorMesa = [];
                foreach ($this->data['agenda_jogos'] as $jogo) {
                    $jogosPorMesa[$jogo['mesa']][] = $jogo;
                }
                
                // Ordenar para garantir que Mesa 1 venha antes da Mesa 2, etc.
                ksort($jogosPorMesa);

                foreach ($jogosPorMesa as $numeroMesa => $jogos): 
                ?>
                    <div class="mesa-card">
                        
                        <div class="mesa-header">
                            <span>🏓 Mesa <?= $numeroMesa ?></span>
                            
                            <form method="POST" action="" style="margin: 0; display: flex; gap: 5px;">
                                <input type="hidden" name="mesa" value="<?= $numeroMesa ?>">
                                <select name="arbitro_id" style="font-size: 11px; padding: 3px; border-radius: 4px; border: none; color: #333; outline: none; max-width: 140px;">
                                    <option value="">Sem Árbitro</option>
                                    <?php if(!empty($this->data['arbitros'])): ?>
                                        <?php foreach($this->data['arbitros'] as $arb): 
                                            $selected = (isset($jogos[0]['arbitro_id']) && $jogos[0]['arbitro_id'] == $arb['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?= $arb['id'] ?>" <?= $selected ?>><?= $arb['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <button type="submit" name="AdmsAtribuirArbitro" value="Atribuir" style="background: #28a745; color: white; border: none; border-radius: 4px; font-size: 11px; padding: 3px 8px; cursor: pointer; font-weight: bold;">
                                    Escalar
                                </button>
                            </form>
                        </div>

                        <?php foreach ($jogos as $index => $j): ?>
                            <div class="mesa-jogo">
                                <div>
                                    <small style="color: #666; display: block; margin-bottom: 3px;">
                                        Jogo <?= $index + 1 ?> (<?= $j['fase'] ?>) 
                                        
                                        <?php if (!empty($j['cat_nome'])): ?>
                                            <span style="background: #17a2b8; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin: 0 5px;">
                                                <?= $j['cat_nome'] ?>
                                            </span>
                                        <?php endif; ?>

                                        - <strong style="color: #0044cc;">
                                            ⌚ <?= !empty($j['horario_previsto']) ? date('H:i', strtotime($j['horario_previsto'])) : 'A definir' ?>
                                        </strong>
                                    </small>
                                    <span class="atleta-nome"><?= $j['atleta_a'] ?></span>
                                    <span class="vs">X</span>
                                    <span class="atleta-nome"><?= $j['atleta_b'] ?></span>
                                </div>
                                
                                <a href="<?= URLADM ?>edit-partida/index/<?= $j['id'] ?>" class="btn-lancar">Lançar Placar</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
             <div style="margin-top: 20px; padding: 20px; text-align: center; background: #fff; border: 1px dashed #ccc;">
                 <p style="color: #666;">Não existem jogos distribuídos em mesas no momento.</p>
             </div>
        <?php endif; ?>

    </div>
</div>