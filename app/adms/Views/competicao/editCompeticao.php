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

            <form method="POST" action="" class="form-adm">
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

                <div style="background: #eef2fa; padding: 20px; border-radius: 8px; border: 1px solid #cce5ff; margin-bottom: 20px; margin-top: 10px;">
                    <label class="title-input" style="color: #0044cc; font-size: 18px; margin-bottom: 15px;">🏆 Categorias Disponíveis Neste Evento</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 15px;">
                        
                        <?php if (!empty($categoriasCadastradas)): ?>
                            <?php foreach ($categoriasCadastradas as $cat): 
                                // Verifica se a categoria já estava salva no torneio
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

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Observações (Regulamento / Avisos)</label>
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