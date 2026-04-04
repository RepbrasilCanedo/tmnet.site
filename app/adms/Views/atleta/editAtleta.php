<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$form = $this->data['form'] ?? null;
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Atleta: <?= $form['nome'] ?></span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-atletas/index" class="btn-info">Listar</a>
            </div>
        </div>

        <div class="content-adm">
            <form method="POST" action="" class="form-adm">
                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome Completo</label>
                        <input type="text" name="nome" class="input-adm" value="<?= $form['nome'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Apelido</label>
                        <input type="text" name="apelido" class="input-adm" value="<?= $form['apelido'] ?? '' ?>">
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Estilo de Jogo</label>
                        <select name="estilo_jogo" class="input-adm">
                            <option value="Classista" <?= ($form['estilo_jogo'] == 'Classista') ? 'selected' : '' ?>>Classista</option>
                            <option value="Caneteiro" <?= ($form['estilo_jogo'] == 'Caneteiro') ? 'selected' : '' ?>>Caneteiro</option>
                            <option value="Semiclassista" <?= ($form['estilo_jogo'] == 'Semiclassista') ? 'selected' : '' ?>>Semiclassista</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Mão Dominante</label>
                        <select name="mao_dominante" class="input-adm">
                            <option value="Destro" <?= ($form['mao_dominante'] == 'Destro') ? 'selected' : '' ?>>Destro</option>
                            <option value="Canhoto" <?= ($form['mao_dominante'] == 'Canhoto') ? 'selected' : '' ?>>Canhoto</option>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Pontuação Ranking (Ajuste Manual)</label>
                        <input type="number" name="pontuacao_ranking" class="input-adm" value="<?= $form['pontuacao_ranking'] ?? '0' ?>">
                    </div>
                </div>

                <button type="submit" name="AdmsEditAtleta" class="btn-warning" value="Salvar">Salvar Alterações</button>
            </form>
        </div>
    </div>
</div>