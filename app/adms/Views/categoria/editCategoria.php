<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$valorForm = $this->data['form'] ?? [];
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Categoria</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-categorias/index" class="btn-info">Voltar</a>
            </div>
        </div>

        <div class="content-adm">
            <form method="POST" action="" class="form-adm">
                <input type="hidden" name="id" value="<?= $valorForm['id'] ?? '' ?>">
                
                <div class="row-input">
                    <div class="column" style="width: 100%;">
                        <label class="title-input">Nome da Categoria ou Divisão:<span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="input-adm" value="<?= $valorForm['nome'] ?? '' ?>" required>
                    </div>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px;">
                    <h5 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Faixa Etária (Idade em Anos)</h5>
                    <div class="row-input" style="margin-bottom: 0;">
                        <div class="column">
                            <label class="title-input">Idade Mínima:</label>
                            <input type="number" name="idade_minima" class="input-adm" value="<?= $valorForm['idade_minima'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input">Idade Máxima:</label>
                            <input type="number" name="idade_maxima" class="input-adm" value="<?= $valorForm['idade_maxima'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
                    <h5 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Nível Técnico (Pontuação no Ranking)</h5>
                    <div class="row-input" style="margin-bottom: 0;">
                        <div class="column">
                            <label class="title-input">Pontuação Mínima:</label>
                            <input type="number" name="pontuacao_minima" class="input-adm" value="<?= $valorForm['pontuacao_minima'] ?? '' ?>">
                        </div>
                        <div class="column">
                            <label class="title-input">Pontuação Máxima:</label>
                            <input type="number" name="pontuacao_maxima" class="input-adm" value="<?= $valorForm['pontuacao_maxima'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <button type="submit" name="SendEditCategoria" class="btn-warning" value="Salvar" style="width: 100%; padding: 12px; font-weight: bold;">💾 Atualizar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>