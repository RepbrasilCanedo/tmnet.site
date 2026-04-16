<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Competição Oficial - TMNet</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-competicoes/index" class="btn-info">Listar Torneios</a>
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
                    <div class="column">
                        <label class="title-input">Nome do Torneio (Oficial)<span class="text-danger">*</span></label>
                        <input type="text" name="nome_torneio" class="input-adm" placeholder="Ex: TMB Estadual - 1ª Etapa" value="<?= $this->data['form']['nome_torneio'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Data do Evento<span class="text-danger">*</span></label>
                        <input type="date" name="data_evento" class="input-adm" value="<?= $this->data['form']['data_evento'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Horário de Início<span class="text-danger">*</span></label>
                        <input type="time" name="horario_inicio" class="input-adm" value="<?= $this->data['form']['horario_inicio'] ?? '08:00' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nível / Chancela do Torneio</label>
                        <select name="categoria_cbtm" class="input-adm">
                            <option value="Campeonato Estadual">Campeonato Estadual</option>
                            <option value="Etapa">Etapa</option>
                            <option value="Torneio Aberto">Torneio Aberto</option>
                            <option value="Liga Regional">Liga Regional / Municipal</option>
                            <option value="Torneio Interno">Torneio Interno (Clube)</option>
                            <option value="Festival">Festival / Iniciante</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Fator Multiplicador (Peso Ranking)</label>
                        <select name="fator_multiplicador" class="input-adm">
                            <option value="1.00">Peso 1.00 </option>
                            <option value="1.50">Peso 1.50</option>
                            <option value="2.00">Peso 2.00 </option>
                        </select>
                    </div>
                </div>

                <div style="background: #eef2fa; padding: 15px; border-radius: 8px; border: 1px solid #0044cc; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #0044cc; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Categorias e Divisões em Disputa</h5>
                    <p style="font-size: 13px; color: #555; margin-bottom: 10px;">Selecione quais categorias irão acontecer neste torneio. Apenas atletas que cumprirem os requisitos de Idade e Rating poderão se inscrever nas opções marcadas abaixo.</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                        <?php if (!empty($this->data['categorias_clube'])): ?>
                            <?php foreach ($this->data['categorias_clube'] as $cat): ?>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; background: #fff; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    <input type="checkbox" name="categorias_ids[]" value="<?= $cat['id'] ?>" style="width: 16px; height: 16px; cursor: pointer;">
                                    <span style="font-size: 14px; font-weight: bold; color: #333;"><?= $cat['nome'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div style="grid-column: 1 / -1; background: #fff3cd; color: #856404; padding: 10px; border-radius: 4px; border: 1px solid #ffeeba;">
                                ⚠️ Nenhuma categoria cadastrada no seu clube. <a href="<?= URLADM ?>add-categoria/index" style="color: #0044cc; font-weight: bold;">Clique aqui para criar</a> antes de agendar o torneio.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #155724; border-bottom: 1px solid #c3e6cb; padding-bottom: 5px;">💰 Valores e Forma de Pagamento</h5>
                    <p style="font-size: 13px; color: #155724; margin-bottom: 15px;">Defina os valores das inscrições. O limite do sistema é de 2 categorias por atleta.</p>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Valor para UMA Categoria (R$):</label>
                            <input type="text" name="valor_uma_categoria" class="input-adm" placeholder="Ex: 50.00" value="<?= $this->data['form']['valor_uma_categoria'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Valor para DUAS Categorias (R$):</label>
                            <input type="text" name="valor_duas_categorias" class="input-adm" placeholder="Ex: 80.00" value="<?= $this->data['form']['valor_duas_categorias'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Chave PIX (Para receber):<span class="text-danger">*</span></label>
                            <input type="text" name="chave_pix" class="input-adm" placeholder="E-mail, CPF, Celular..." value="<?= $this->data['form']['chave_pix'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Local / Ginásio</label>
                        <input type="text" name="local_evento" class="input-adm" placeholder="Ex: Sede do Clube / Ginásio Poliesportivo" value="<?= $this->data['form']['local_evento'] ?? '' ?>">
                    </div>
                    <div class="column">
                        <label class="title-input">Sistema de Disputa</label>
                        <select name="sistema_disputa" class="input-adm">
                            <option value="1">Grupos de 3 (Passam 2) + Mata-mata</option>
                            <option value="2">Chave Única (Todos contra todos)</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Gênero da Competição</label>
                        <select name="tipo_genero" class="input-adm">
                            <option value="1">Misto (Homens e Mulheres juntos)</option>
                            <option value="2">Separado por Gênero (Masc / Fem independentes)</option>
                        </select>
                    </div>
                </div>

                <div style="background: #fdf5e6; padding: 20px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 20px; margin-top: 10px;">
                    <label class="title-input" style="color: #856404; font-size: 18px; margin-bottom: 5px;">🏆 Pontuação para o Ranking Geral</label>
                    <p style="font-size: 13px; color: #666; margin-top: 0; margin-bottom: 15px;">Configure os pontos que este torneio distribuirá. <br><i>Se deixar tudo Zerado, o torneio será considerado "Amistoso" e não valerá pontos para o ranking.</i></p>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #d4af37;">🥇 Campeão (1º)</label>
                            <input type="number" name="pts_campeao" class="input-adm" placeholder="Ex: 300" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #a9a9a9;">🥈 Vice (2º)</label>
                            <input type="number" name="pts_vice" class="input-adm" placeholder="Ex: 150" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #cd7f32;">🥉 Terceiros (Semi)</label>
                            <input type="number" name="pts_terceiro" class="input-adm" placeholder="Ex: 100" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #555;">🏅 5º ao 8º (Quartas)</label>
                            <input type="number" name="pts_quartas" class="input-adm" placeholder="Ex: 50" value="0">
                        </div>
                    </div>
                    
                    <hr style="border-top: 1px dashed #ccc; margin: 15px 0;">
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #17a2b8;">🤝 Pts por Participação</label>
                            <input type="number" name="pts_participacao" class="input-adm" placeholder="Ex: 50" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #28a745;">✅ Pts por Vitória (Fixa)</label>
                            <input type="number" name="pts_vitoria_jogo" class="input-adm" placeholder="Ex: 10" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #dc3545;">❌ Pts por Derrota (Fixa)</label>
                            <input type="number" name="pts_derrota_jogo" class="input-adm" placeholder="Ex: 5" value="0">
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Observações do Torneio</label>
                        <textarea name="observacoes" class="input-adm" rows="3" placeholder="Informações sobre inscrições, taxas ou regulamento específico..."><?= $this->data['form']['observacoes'] ?? '' ?></textarea>
                    </div>
                </div>

                <div class="button-area" style="margin-top: 20px;">
                    <button type="submit" name="AdmsAddComp" class="btn-success" value="Cadastrar" style="background-color: #0044cc; font-size: 16px; padding: 12px 20px;">
                        🏆 Criar Competição Oficial
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>