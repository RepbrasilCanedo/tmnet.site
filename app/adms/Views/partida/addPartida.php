<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Lançar Resultado - TMNet</span>
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
                <input type="hidden" name="adms_competicao_id" value="<?= $this->data['competicao_id'] ?>">

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Atleta A</label>
                        <select name="atleta_a_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['atletas'] as $atleta) {
                                echo "<option value='{$atleta['id']}'>{$atleta['name']} - {$atleta['apelido']}</option>";
                            } ?>
                        </select>
                        <input type="number" name="sets_atleta_a" class="input-adm" placeholder="Sets" min="0" required>
                    </div>

                    <div class="column" style="display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 24px; padding-top: 25px;">
                        VS
                    </div>

                    <div class="column">
                        <label class="title-input">Atleta B</label>
                        <select name="atleta_b_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['atletas'] as $atleta) {
                                echo "<option value='{$atleta['id']}'>{$atleta['name']} - {$atleta['apelido']}</option>";
                            } ?>
                        </select>
                        <input type="number" name="sets_atleta_b" class="input-adm" placeholder="Sets" min="0" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Fase</label>
                        <select name="fase" class="input-adm">
                            <option value="Grupo">Fase de Grupos</option>
                            <option value="Oitavas">Oitavas de Final</option>
                            <option value="Quartas">Quartas de Final</option>
                            <option value="Semi">Semifinal</option>
                            <option value="Final">Final</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="AdmsAddPartida" class="btn-success" value="Salvar" style="background-color: #0044cc;">Registrar Partida</button>
            </form>
        </div>
    </div>
</div>