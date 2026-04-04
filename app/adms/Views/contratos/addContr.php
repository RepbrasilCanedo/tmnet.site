<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Contratos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_contr']) {
                    echo "<a href='" . URLADM . "list-contr/index' class='btn-info'>Listar</a> ";
                }
                ?>
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
            <form method="POST" action="" id="form-add-contr" class="form-adm">

                <div class="row-input">
                    <div class="column">
                    <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Tipo Contrato:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Tipo Contrato" value="<?php echo $name; ?>" required>

                    </div>
                    <div class="column">
                        <label class="title-input">Situação do Contrato:<span class="text-danger">*</span></label>
                        <select name="sit_cont" id="sit_cont" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['name_sit'] as $sitContr) {
                                extract($sitContr);
                                if (isset($valorForm['name_sit']) and $valorForm['name_sit'] == $id_sit) {
                                    echo "<option value='$id_sit' selected>$name_sit </option>";
                                } else {
                                    echo "<option value='$id_sit'>$name_sit </option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                    

                <p class="text-danger mb-5 fs-6">* Campo Obrigatório</p>

                <button type="submit" name="SendAddContr" class="btn-success" value="Cadastrar">Cadastrar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->