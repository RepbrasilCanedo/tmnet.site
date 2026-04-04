<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Atleta</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>list-atletas/index" class="btn-info">Listar Atletas</a>
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
                        <label class="title-input">Nome Completo</label>
                        <input type="text" name="nome" class="input-adm" placeholder="Nome do atleta" value="<?= $this->data['form']['nome'] ?? '' ?>" required>
                    </div>
                    <div class="column">
                        <label class="title-input">Apelido</label>
                        <input type="text" name="apelido" class="input-adm" placeholder="Como é conhecido" value="<?= $this->data['form']['apelido'] ?? '' ?>">
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Estilo de Jogo</label>
                        <select name="estilo_jogo" class="input-adm">
                            <option value="">Selecione</option>
                            <option value="Classista">Classista</option>
                            <option value="Caneteiro">Caneteiro</option>
                            <option value="Semiclassista">Semiclassista</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Mão Dominante</label>
                        <select name="mao_dominante" class="input-adm">
                            <option value="Destro">Destro</option>
                            <option value="Canhoto">Canhoto</option>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="input-adm" value="<?= $this->data['form']['data_nascimento'] ?? '' ?>">
                    </div>
                </div>

                <button type="submit" name="AdmsAddAtleta" class="btn-success" value="Cadastrar">Cadastrar Atleta</button>
            </form>
        </div>
    </div>
</div>