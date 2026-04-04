<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Nova Categoria / Divisão</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-categorias/index" class="btn-info">Listar Categorias</a>
            </div>
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <span id="msg"></span>
        </div>

        <div class="content-adm">
            
            <div style="background: #eef2fa; padding: 15px; border-radius: 6px; border-left: 4px solid #0044cc; margin-bottom: 20px;">
                <h4 style="color: #0044cc; margin-top: 0; margin-bottom: 10px;">💡 Como criar Categorias e Divisões?</h4>
                <p style="color: #555; font-size: 14px; margin-bottom: 5px;">Você pode combinar Idade e Pontuação para criar regras estritas. Deixe em branco o que não quiser limitar.</p>
                <ul style="color: #555; font-size: 13px; padding-left: 20px; margin-bottom: 0;">
                    <li><strong>Apenas por Idade (Ex: Sub-15):</strong> Idade Mínima vazia / Idade Máxima 15. (Pontuações vazias).</li>
                    <li><strong>Apenas por Nível Técnico (Ex: 5ª Divisão):</strong> Idades vazias. Pontuação Mín. 0 / Pontuação Máx. 400.</li>
                    <li><strong>Híbrido (Ex: Sub-15 Iniciante):</strong> Idade Máx. 15 / Pontuação Máx. 300.</li>
                    <li><strong>Categoria Livre / Absoluto:</strong> Deixe todos os campos numéricos em branco.</li>
                </ul>
            </div>

            <form method="POST" action="" id="form-add-categoria" class="form-adm">
                
                <div class="row-input">
                    <div class="column" style="width: 100%;">
                        <?php $nome = $valorForm['nome'] ?? ""; ?>
                        <label class="title-input">Nome da Categoria ou Divisão:<span class="text-danger">*</span></label>
                        <input type="text" name="nome" id="nome" class="input-adm" placeholder="Ex: Sub-15, 1ª Divisão, Adulto Iniciante..." value="<?= $nome; ?>" required>
                    </div>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 15px;">
                    <h5 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Faixa Etária (Idade em Anos)</h5>
                    <div class="row-input" style="margin-bottom: 0;">
                        <div class="column">
                            <?php $idade_minima = $valorForm['idade_minima'] ?? ""; ?>
                            <label class="title-input">Idade Mínima:</label>
                            <input type="number" name="idade_minima" id="idade_minima" class="input-adm" placeholder="Ex: 18" min="0" value="<?= $idade_minima; ?>">
                        </div>

                        <div class="column">
                            <?php $idade_maxima = $valorForm['idade_maxima'] ?? ""; ?>
                            <label class="title-input">Idade Máxima:</label>
                            <input type="number" name="idade_maxima" id="idade_maxima" class="input-adm" placeholder="Ex: 39" min="0" value="<?= $idade_maxima; ?>">
                        </div>
                    </div>
                </div>

                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd;">
                    <h5 style="margin-top: 0; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px;">Nível Técnico (Pontuação no Ranking)</h5>
                    <div class="row-input" style="margin-bottom: 0;">
                        <div class="column">
                            <?php $pontuacao_minima = $valorForm['pontuacao_minima'] ?? ""; ?>
                            <label class="title-input">Pontuação Mínima:</label>
                            <input type="number" name="pontuacao_minima" id="pontuacao_minima" class="input-adm" placeholder="Ex: 1000" min="0" value="<?= $pontuacao_minima; ?>">
                        </div>

                        <div class="column">
                            <?php $pontuacao_maxima = $valorForm['pontuacao_maxima'] ?? ""; ?>
                            <label class="title-input">Pontuação Máxima:</label>
                            <input type="number" name="pontuacao_maxima" id="pontuacao_maxima" class="input-adm" placeholder="Ex: 1500" min="0" value="<?= $pontuacao_maxima; ?>">
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>
                    <button type="submit" name="SendAddCategoria" class="btn-success" value="Cadastrar" style="background-color: #28a745; color: white; padding: 10px 20px; font-size: 16px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">
                        💾 Salvar Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>