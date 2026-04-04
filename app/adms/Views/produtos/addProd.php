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
            <span class="title-content">Cadastrar Equipamento</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_prod']) {
                    echo "<a href='" . URLADM . "list-prod/index' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-add-prod" class="form-adm">

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome Equipamento:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Nome do produto" value="<?php echo $valorForm['name'] ?? ''; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Tipo do Equipamento:<span class="text-danger">*</span></label>
                        <select name="type_id" id="type_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['type_equip'] as $tipEquip) {
                                extract($tipEquip);
                                if (isset($valorForm['type_id']) and $valorForm['type_id'] == $id_typ) {
                                    echo "<option value='$id_typ' selected>$name_typ</option>";
                                } else {
                                    echo "<option value='$id_typ'>$name_typ</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Número Série: </span></label>
                        <input type="text" name="serie" id="serie" class="input-adm" placeholder="Série do produto" value="<?php echo $valorForm['serie'] ?? ''; ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Modelo do Equipamento:<span class="text-danger">*</span></label>
                        <input type="text" name="modelo_id" id="modelo_id" class="input-adm" placeholder="Modelo do equipamento" value="<?php echo $valorForm['modelo_id'] ?? ''; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Marca do Equipamento:<span class="text-danger">*</span></label>
                        <input type="text" name="marca_id" id="marca_id" class="input-adm" placeholder="Marca do equipamento" value="<?php echo $valorForm['marca_id'] ?? ''; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Empresa / Cliente vinculado:<span class="text-danger">*</span></label>
                        <select name="cliente_id" id="cliente_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['emp_equip'] as $empEquip) {
                                extract($empEquip);
                                if (isset($valorForm['cliente_id']) and $valorForm['cliente_id'] == $id_emp) {
                                    echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                } else {
                                    echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Situação do Equipamento:<span class="text-danger">*</span></label>
                        <select name="sit_id" id="sit_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['sit_equip'] as $sitEquip) {
                                extract($sitEquip);
                                if (isset($valorForm['sit_id']) and $valorForm['sit_id'] == $id_sit) {
                                    echo "<option value='$id_sit' selected>$name_sit</option>";
                                } else {
                                    echo "<option value='$id_sit'>$name_sit</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Informações Adicionais:</label>
                        <textarea name="inf_adicionais" id="inf_adicionais" class="input-adm" placeholder="Observações" required><?php echo $valorForm['inf_adicionais'] ?? ''; ?></textarea>
                    </div>
                </div>

                <p class="text-danger mb-3 fs-6">* Campo Obrigatório</p>

                <button type="submit" name="SendAddProd" class="btn-success" value="Cadastrar">Cadastrar Equipamento</button>

            </form>
        </div>
    </div>
</div>