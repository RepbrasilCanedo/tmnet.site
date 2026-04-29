<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$valorForm = $this->data['form'] ?? [];
$categoriasCadastradas = $this->data['categorias'] ?? [];
$categoriasSelecionadas = $valorForm['categorias_selecionadas'] ?? [];
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Competição</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>view-competicao/index/<?= $valorForm['id'] ?>" class="btn-info">Voltar à Súmula</a>
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
            
            <?php if(isset($valorForm['status_inscricao']) && $valorForm['status_inscricao'] == 0): ?>
                <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; border-left: 5px solid #ffc107; margin-bottom: 20px; font-weight: bold;">
                    ⚠️ Atenção: As inscrições para este torneio estão encerradas. Qualquer alteração aqui não afetará os atletas que já estão inscritos.
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="form-adm" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $valorForm['id'] ?? '' ?>">

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome do Torneio <span class="text-danger">*</span></label>
                        <input type="text" name="nome_torneio" id="nome_torneio" class="input-adm" placeholder="Ex: TMNet Open 2026" value="<?= $valorForm['nome_torneio'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Data do Evento <span class="text-danger">*</span></label>
                        <input type="date" name="data_evento" id="data_evento" class="input-adm" value="<?= $valorForm['data_evento'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Horário de Início <span class="text-danger">*</span></label>
                        <input type="time" name="horario_inicio" id="horario_inicio" class="input-adm" value="<?= $valorForm['horario_inicio'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Local do Evento <span class="text-danger">*</span></label>
                        <input type="text" name="local_evento" id="local_evento" class="input-adm" placeholder="Ginásio / Clube" value="<?= $valorForm['local_evento'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Tipo da Competição <span class="text-danger">*</span></label>
                        <select name="tipo_competicao" id="tipo_competicao" class="input-adm" required>
                            <option value="1" <?= (isset($valorForm['tipo_competicao']) && $valorForm['tipo_competicao'] == 1) ? 'selected' : '' ?>>1 - Livre / Amador</option>
                            <option value="2" <?= (isset($valorForm['tipo_competicao']) && $valorForm['tipo_competicao'] == 2) ? 'selected' : '' ?>>2 - Por Categorias de Ranking</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Sistema de Gênero <span class="text-danger">*</span></label>
                        <select name="tipo_genero" id="tipo_genero" class="input-adm" required>
                            <option value="1" <?= (isset($valorForm['tipo_genero']) && $valorForm['tipo_genero'] == 1) ? 'selected' : '' ?>>1 - Misto (Geral)</option>
                            <option value="2" <?= (isset($valorForm['tipo_genero']) && $valorForm['tipo_genero'] == 2) ? 'selected' : '' ?>>2 - Separado (Masc / Fem)</option>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Sistema de Disputa <span class="text-danger">*</span></label>
                        <select name="sistema_disputa" id="sistema_disputa" class="input-adm" required>
                            <option value="1" <?= (isset($valorForm['sistema_disputa']) && $valorForm['sistema_disputa'] == 1) ? 'selected' : '' ?>>1 - Fase de Grupos + Mata-Mata</option>
                            <option value="2" <?= (isset($valorForm['sistema_disputa']) && $valorForm['sistema_disputa'] == 2) ? 'selected' : '' ?>>2 - Todos Contra Todos</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Categoria Base (Ex: TMB Estadual)</label>
                        <input type="text" name="categoria_cbtm" id="categoria_cbtm" class="input-adm" value="<?= $valorForm['categoria_cbtm'] ?? '' ?>">
                    </div>
                    <div class="column">
                        <label class="title-input">Multiplicador de Pontos (Peso)</label>
                        <input type="number" name="fator_multiplicador" id="fator_multiplicador" class="input-adm" step="0.01" value="<?= $valorForm['fator_multiplicador'] ?? '1.00' ?>">
                    </div>
                </div>

                <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #155724; border-bottom: 1px solid #c3e6cb; padding-bottom: 10px;">💰 Valores e Forma de Pagamento</h5>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Geral: 1 Categoria (R$)</label>
                            <input type="text" name="valor_uma_categoria" class="input-adm" placeholder="Ex: 50.00" value="<?= $valorForm['valor_uma_categoria'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Geral: 2 Categorias (R$)</label>
                            <input type="text" name="valor_duas_categorias" class="input-adm" placeholder="Ex: 80.00" value="<?= $valorForm['valor_duas_categorias'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #0044cc;">Sócio/Convênio: 1 Cat (R$)</label>
                            <input type="text" name="valor_uma_socio" class="input-adm" placeholder="Ex: 40.00" value="<?= $valorForm['valor_uma_socio'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #0044cc;">Sócio/Convênio: 2 Cat (R$)</label>
                            <input type="text" name="valor_duas_socio" class="input-adm" placeholder="Ex: 70.00" value="<?= $valorForm['valor_duas_socio'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #e67e22;">Estudante: 1 Cat (R$)</label>
                            <input type="text" name="valor_uma_estudante" class="input-adm" placeholder="Ex: 25.00" value="<?= $valorForm['valor_uma_estudante'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #e67e22;">Estudante: 2 Cat (R$)</label>
                            <input type="text" name="valor_duas_estudante" class="input-adm" placeholder="Ex: 45.00" value="<?= $valorForm['valor_duas_estudante'] ?? '' ?>">
                        </div>
                    </div>

                    <div class="row-input" style="margin-top: 15px;">
                        <div class="column">
                            <label class="title-input" style="color: #155724;">Chave PIX (Para receber):<span class="text-danger">*</span></label>
                            <input type="text" name="chave_pix" class="input-adm" placeholder="E-mail, CPF, Celular..." value="<?= $valorForm['chave_pix'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div style="background: #fdf5e6; padding: 20px; border-radius: 8px; border: 1px solid #ffeeba; margin-bottom: 20px; margin-top: 10px;">
                    <label class="title-input" style="color: #856404; font-size: 18px; margin-bottom: 5px;">🏆 Pontuação para o Ranking Geral</label>
                    <p style="font-size: 13px; color: #666; margin-top: 0; margin-bottom: 15px;">Configure os pontos que este torneio distribuirá. <br><i>Se deixar tudo Zerado, o torneio será considerado "Amistoso" e não valerá pontos para o ranking.</i></p>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #d4af37;">🥇 Campeão (1º)</label>
                            <input type="number" name="pts_campeao" class="input-adm" placeholder="Ex: 300" value="<?= $valorForm['pts_campeao'] ?? '0' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #a9a9a9;">🥈 Vice (2º)</label>
                            <input type="number" name="pts_vice" class="input-adm" placeholder="Ex: 150" value="<?= $valorForm['pts_vice'] ?? '0' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #cd7f32;">🥉 Terceiros (Semi)</label>
                            <input type="number" name="pts_terceiro" class="input-adm" placeholder="Ex: 100" value="<?= $valorForm['pts_terceiro'] ?? '0' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #555;">🏅 5º ao 8º (Quartas)</label>
                            <input type="number" name="pts_quartas" class="input-adm" placeholder="Ex: 50" value="<?= $valorForm['pts_quartas'] ?? '0' ?>">
                        </div>
                    </div>
                    
                    <hr style="border-top: 1px dashed #ccc; margin: 15px 0;">
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input" style="color: #17a2b8;">🤝 Pts por Participação</label>
                            <input type="number" name="pts_participacao" class="input-adm" placeholder="Ex: 50" value="<?= $valorForm['pts_participacao'] ?? '0' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #28a745;">✅ Pts por Vitória (Fixa)</label>
                            <input type="number" name="pts_vitoria_jogo" class="input-adm" placeholder="Ex: 10" value="<?= $valorForm['pts_vitoria_jogo'] ?? '0' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input" style="color: #dc3545;">❌ Pts por Derrota (Fixa)</label>
                            <input type="number" name="pts_derrota_jogo" class="input-adm" placeholder="Ex: 5" value="<?= $valorForm['pts_derrota_jogo'] ?? '0' ?>">
                        </div>
                    </div>
                </div>

                <div style="background: #eef2fa; padding: 20px; border-radius: 8px; border: 1px solid #cce5ff; margin-bottom: 20px; margin-top: 10px;">
                    <label class="title-input" style="color: #0044cc; font-size: 18px; margin-bottom: 15px;">🏆 Categorias Disponíveis Neste Evento</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                        
                        <?php if (!empty($categoriasCadastradas)): ?>
                            <?php foreach ($categoriasCadastradas as $cat): 
                                $checked = in_array((string)$cat['id'], $categoriasSelecionadas) ? 'checked' : '';
                            ?>
                                <label style="background: #fff; padding: 10px; border-radius: 6px; border: 1px solid #ddd; display: flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                    <input type="checkbox" name="categorias_selecionadas[]" value="<?= $cat['id'] ?>" <?= $checked ?> style="width: 18px; height: 18px; cursor: pointer;">
                                    <span style="font-weight: bold; color: #333;"><?= $cat['nome'] ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: #dc3545; grid-column: 1 / -1;">Nenhuma categoria cadastrada no clube. Vá ao menu 'Categorias' primeiro.</p>
                        <?php endif; ?>

                    </div>
                </div>

                <div style="background: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 20px;">
                    <h5 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;"><i class="fa-solid fa-file-pdf" style="color:#dc3545;"></i> Anexar Regulamento (PDF)</h5>
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Selecione o arquivo PDF</label>
                            <input type="file" name="regulamento" id="regulamento" class="input-adm" accept="application/pdf">
                            
                            <?php if (!empty($valorForm['regulamento']) && file_exists("app/adms/assets/arquivos/competicao/" . $valorForm['id'] . "/" . $valorForm['regulamento'])): ?>
                                <div style="margin-top: 15px;">
                                    <a href="<?= URLADM ?>app/adms/assets/arquivos/competicao/<?= $valorForm['id'] ?>/<?= $valorForm['regulamento'] ?>" target="_blank" class="btn-info" style="padding: 8px 15px; font-size: 13px; border-radius: 4px; text-decoration: none; background: #17a2b8; color: white;">
                                        <i class="fa-solid fa-eye"></i> Visualizar Regulamento Atual
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Observações Adicionais</label>
                        <textarea name="observacoes" id="observacoes" class="input-adm" rows="4"><?= $valorForm['observacoes'] ?? '' ?></textarea>
                    </div>
                </div>

                <button type="submit" name="SendEditComp" value="Salvar" class="btn-success" style="background-color: #0044cc; font-size: 16px; padding: 10px 30px; border: none; border-radius: 4px; color: white; cursor: pointer; font-weight: bold; width: 100%;">
                    💾 Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</div>