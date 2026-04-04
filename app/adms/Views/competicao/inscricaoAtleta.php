<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<style>
    .grid-torneios { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px; margin-top: 20px; }
    .card-torneio { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden; border-top: 5px solid #0044cc; transition: transform 0.2s; }
    .card-torneio:hover { transform: translateY(-3px); }
    .card-body { padding: 20px; flex-grow: 1; }
    .card-title { margin: 0 0 10px 0; color: #333; font-size: 18px; }
    .card-info { font-size: 14px; color: #555; margin-bottom: 5px; }
    .card-info strong { color: #333; }
    
    .card-footer { margin-top: auto; padding: 15px 20px; background-color: #f8f9fa; border-top: 1px solid #eee; }
    
    .btn-inscrever { background-color: #0044cc; color: white; border: none; padding: 10px 0; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 15px; margin-bottom: 5px; }
    .btn-atualizar { background-color: #17a2b8; color: white; border: none; padding: 10px 0; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 15px; margin-bottom: 5px; }
    .btn-cancelar { background-color: #dc3545; color: white; border: none; padding: 10px 0; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 15px; }
    .status-badge { display: inline-block; background-color: #28a745; color: white; padding: 3px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; margin-bottom: 10px; }
    .tipo-badge { display: inline-block; background-color: #6c757d; color: white; padding: 3px 10px; border-radius: 4px; font-size: 11px; margin-left: 5px; }
    
    .box-divisao { border: 1px solid #cce5ff; background: #eef2fa; padding: 12px; border-radius: 6px; margin-bottom: 15px; }
    .box-divisao label { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; font-size: 14px; font-weight: bold; color: #0044cc; background: #fff; padding: 8px; border-radius: 4px; border: 1px solid #ddd; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list" style="display: flex; justify-content: space-between; align-items: center;">
            <span class="title-content" style="margin: 0;">Calendário de Torneios</span>
            
            <div class="top-list-right" style="display: flex; gap: 10px; align-items: center; margin: 0;">
                <span style="color: #666; font-size: 14px;">Mantenha a sua raquete pronta!</span>
                <a href="<?= URLADM ?>perfil-atleta/index" class="btn-info" style="margin: 0;">Meu Perfil</a>
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

        <?php if (!empty($this->data['torneios'])): ?>
            <div class="grid-torneios">
                <?php foreach ($this->data['torneios'] as $torneio): 
                    $dataFormatada = date('d/m/Y', strtotime($torneio['data_evento']));
                    $jaInscrito = !empty($torneio['categorias_inscritas']);
                    
                    // Transforma "1,3" num array para checar as checkboxes
                    $arrayInscritas = $jaInscrito ? explode(',', $torneio['categorias_inscritas']) : [];
                ?>
                    <div class="card-torneio">
                        <div class="card-body">
                            <?php if ($jaInscrito): ?>
                                <span class="status-badge">✓ INSCRITO</span>
                            <?php endif; ?>
                            
                            <h3 class="card-title">
                                <?= $torneio['nome_torneio'] ?>
                                <span class="tipo-badge"><?= $torneio['categoria_cbtm'] ?></span>
                            </h3>
                            <div class="card-info"><strong>🗓️ Data:</strong> <?= $dataFormatada ?></div>
                            <div class="card-info"><strong>📍 Local:</strong> <?= $torneio['local_evento'] ?></div>
                            <div class="card-info"><strong>⭐ Peso (Ranking):</strong> x<?= number_format($torneio['fator_multiplicador'], 1) ?></div>
                        </div>
                        
                        <div class="card-footer">
                            <form method="POST" action="">
                                <input type="hidden" name="competicao_id" value="<?= $torneio['id'] ?>">
                                
                                <div class="box-divisao">
                                    <strong style="display: block; margin-bottom: 10px; font-size: 13px; color: #333;">Categorias Disponíveis (pelo seu Nível/Idade):</strong>
                                    
                                    <?php if (!empty($torneio['categorias_elegiveis'])): ?>
                                        <?php foreach ($torneio['categorias_elegiveis'] as $cat): 
                                            $checked = in_array($cat['id'], $arrayInscritas) ? 'checked' : '';
                                        ?>
                                            <label>
                                                <input type="checkbox" name="categorias_selecionadas[]" value="<?= $cat['id'] ?>" <?= $checked ?> style="width: 16px; height: 16px;"> 
                                                <?= $cat['nome'] ?>
                                            </label>
                                        <?php endforeach; ?>
                                        <small style="color: #666; font-size: 11px; display: block; margin-top: 8px;">*Pode jogar na sua categoria e inscrever-se num desafio acima.</small>
                                    
                                    <?php else: ?>
                                        <span style="color: #dc3545; font-size: 13px; font-weight: bold; display: block; text-align: center; padding: 10px; background: #fff; border-radius: 4px;">🚫 Nenhuma categoria aberta para a sua Idade/Rating.</span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($torneio['categorias_elegiveis'])): // Só mostra botão se tiver opção para jogar ?>
                                    <?php if ($jaInscrito): ?>
                                        <button type="submit" name="AdmsAtualizar" value="Atualizar" class="btn-atualizar">
                                            🔄 Atualizar Inscrição
                                        </button>
                                        <button type="submit" name="AdmsCancelar" value="Cancelar" class="btn-cancelar" onclick="return confirm('Tem a certeza que deseja cancelar todas as inscrições neste torneio?');">
                                            ❌ Cancelar Inscrição
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="AdmsInscrever" value="Inscrever" class="btn-inscrever">
                                            ✅ Quero Participar!
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="content-adm" style="text-align: center; padding: 40px; color: #666;">
                <h3 style="color: #0044cc;">Nenhum torneio aberto no momento</h3>
                <p>Fique atento! Em breve a organização lançará novas competições.</p>
            </div>
        <?php endif; ?>

    </div>
</div>