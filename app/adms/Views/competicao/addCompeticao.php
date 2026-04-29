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
                            <option value="Torneio Aberto">Torneio Aberto</option>
                            <option value="Torneio Interno">Torneio Interno (Clube)</option>
                            <option value="Campeonato Estadual">Campeonato</option>
                            <option value="Etapa">Etapa</option>
                            <option value="Festival">Rachão</option>
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
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 10px;">
                        <?php if (!empty($this->data['categorias_clube'])): ?>
                            <?php foreach ($this->data['categorias_clube'] as $cat): ?>
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; background: #fff; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                    <input type="checkbox" name="categorias_ids[]" value="<?= $cat['id'] ?>" style="width: 16px; height: 16px;">
                                    <span style="font-size: 14px; font-weight: bold; color: #333;"><?= $cat['nome'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #155724; border-bottom: 1px solid #c3e6cb; padding-bottom: 10px;">💰 Tabela de Valores das Inscrições</h5>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Geral: 1 Categoria (R$)</label>
                            <input type="text" name="valor_uma_categoria" class="input-adm" placeholder="Ex: 50,00" value="<?= $this->data['form']['valor_uma_categoria'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Geral: 2 Categorias (R$)</label>
                            <input type="text" name="valor_duas_categorias" class="input-adm" placeholder="Ex: 80,00" value="<?= $this->data['form']['valor_duas_categorias'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #0044cc;">Sócio/Convênio: 1 Cat (R$)</label>
                            <input type="text" name="valor_uma_socio" class="input-adm" placeholder="Ex: 40,00" value="<?= $this->data['form']['valor_uma_socio'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #0044cc;">Sócio/Convênio: 2 Cat (R$)</label>
                            <input type="text" name="valor_duas_socio" class="input-adm" placeholder="Ex: 70,00" value="<?= $this->data['form']['valor_duas_socio'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #e67e22;">Estudante: 1 Cat (R$)</label>
                            <input type="text" name="valor_uma_estudante" class="input-adm" placeholder="Ex: 25,00" value="<?= $this->data['form']['valor_uma_estudante'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #e67e22;">Estudante: 2 Cat (R$)</label>
                            <input type="text" name="valor_duas_estudante" class="input-adm" placeholder="Ex: 45,00" value="<?= $this->data['form']['valor_duas_estudante'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Chave PIX do Clube:<span class="text-danger">*</span></label>
                            <input type="text" name="chave_pix" class="input-adm" placeholder="E-mail, CPF, Celular..." value="<?= $this->data['form']['chave_pix'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Local / Ginásio</label>
                        <input type="text" name="local_evento" class="input-adm" value="<?= $this->data['form']['local_evento'] ?? '' ?>">
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
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #d4af37;">🥇 Campeão (1º)</label>
                            <input type="number" name="pts_campeao" class="input-adm" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #a9a9a9;">🥈 Vice (2º)</label>
                            <input type="number" name="pts_vice" class="input-adm" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #cd7f32;">🥉 Terceiros (Semi)</label>
                            <input type="number" name="pts_terceiro" class="input-adm" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #555;">🏅 5º ao 8º (Quartas)</label>
                            <input type="number" name="pts_quartas" class="input-adm" value="0">
                        </div>
                    </div>
                    <hr style="border-top: 1px dashed #ccc; margin: 15px 0;">
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #17a2b8;">🤝 Participação</label>
                            <input type="number" name="pts_participacao" class="input-adm" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #28a745;">✅ Vitória</label>
                            <input type="number" name="pts_vitoria_jogo" class="input-adm" value="0">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #dc3545;">❌ Derrota</label>
                            <input type="number" name="pts_derrota_jogo" class="input-adm" value="0">
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Observações do Torneio</label>
                        <textarea name="observacoes" class="input-adm" rows="3"><?= $this->data['form']['observacoes'] ?? '' ?></textarea>
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