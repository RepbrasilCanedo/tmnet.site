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
            <span class="title-content">Cadastrar Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_equip']) {
                    echo "<a href='" . URLADM . "list-equip/index' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-add-equip" class="form-adm">

                <?php if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) { ?>

                    <div class="row-input">
                        <div class="column">
                            <?php
                            $name = "";
                            if (isset($valorForm['name'])) {
                                $name = $valorForm['name'];
                            }
                            ?>
                            <label class="title-input">Nome Equipamento:<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="input-adm" placeholder="Nome do equipamento" value="<?php echo $name; ?>" required>

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
                            <?php
                            $serie = "";
                            if (isset($valorForm['serie'])) {
                                $serie = $valorForm['serie'];
                            }
                            ?>
                            <label class="title-input">Número Série: </span></label>
                            <input type="text" name="serie" id="serie" class="input-adm" placeholder="Serie do equipamento" value="<?php echo $serie; ?>" required>

                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Modelo do Equipamento:<span class="text-danger">*</span></label>
                            <select name="modelo_id" id="modelo_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['mod_equip'] as $modeloEquip) {
                                    extract($modeloEquip);
                                    if (isset($valorForm['modelo_id']) and $valorForm['modelo_id'] == $id_modelo) {
                                        echo "<option value='$id_modelo' selected>$name_modelo</option>";
                                    } else {
                                        echo "<option value='$id_modelo'>$name_modelo</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <label class="title-input">Marca do Equipamento:<span class="text-danger">*</span></label>
                            <select name="marca_id" id="marca_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['marca_equip'] as $marcaEquip) {
                                    extract($marcaEquip);
                                    if (isset($valorForm['marca_id']) and $valorForm['marca_id'] == $id_mar) {
                                        echo "<option value='$id_mar' selected>$name_mar</option>";
                                    } else {
                                        echo "<option value='$id_mar'>$name_mar</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Empresa do Equipamento:<span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_equip'] as $empEquip) {
                                    extract($empEquip);
                                    if (isset($valorForm['empresa_id']) and $valorForm['empresa_id'] == $id_emp) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

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
                    </div>

                <?php } else { ?>

                    <div class="row-input">
                        <div class="column">
                            <?php
                            $name = "";
                            if (isset($valorForm['name'])) {
                                $name = $valorForm['name'];
                            }
                            ?>
                            <label class="title-input">Nome Equipamento:<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="input-adm" placeholder="Nome do equipamento" value="<?php echo $name; ?>" required>

                        </div>
                        <div class="column">
                            <label class="title-input">Tipo do Equipamento:<span class="text-danger">*</span></label>
                            <select name="type_id" id="type_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['type_equip'] as $sitEquip) {
                                    extract($sitEquip);
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
                            <?php
                            $serie = "";
                            if (isset($valorForm['serie'])) {
                                $serie = $valorForm['serie'];
                            }
                            ?>
                            <label class="title-input">Número Série: </span></label>
                            <input type="text" name="serie" id="serie" class="input-adm" placeholder="Serie do equipamento" value="<?php echo $serie; ?>" required>

                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Modelo do Equipamento:<span class="text-danger">*</span></label>
                            <select name="modelo_id" id="modelo_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['mod_equip'] as $modeloEquip) {
                                    extract($modeloEquip);
                                    if (isset($valorForm['modelo_id']) and $valorForm['modelo_id'] == $id_modelo) {
                                        echo "<option value='$id_modelo' selected>$name_modelo</option>";
                                    } else {
                                        echo "<option value='$id_modelo'>$name_modelo</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <label class="title-input">Marca do Equipamento:<span class="text-danger">*</span></label>
                            <select name="marca_id" id="marca_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['marca_equip'] as $marcaEquip) {
                                    extract($marcaEquip);
                                    if (isset($valorForm['marca_id']) and $valorForm['marca_id'] == $id_mar) {
                                        echo "<option value='$id_mar' selected>$name_mar</option>";
                                    } else {
                                        echo "<option value='$id_mar'>$name_mar</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-input">


                        <div class="column">
                            <label class="title-input">Contratos:<span class="text-danger">*</span></label>
                            <select name="cont_id" id="cont_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_cont'] as $empCont) {
                                    extract($empCont);

                                    if (isset($valorForm['cont_id']) and $valorForm['cont_id'] == $id_cont) {
                                        echo "<option value='$id_cont' selected>$num_cont</option>";
                                    } else {
                                        echo "<option value='$id_cont'>$num_cont </option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <label class="title-input">Empresa do Equipamento:<span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_equip'] as $empEquip) {
                                    extract($empEquip);
                                    if (isset($valorForm['empresa_id']) and $valorForm['empresa_id'] == $id_emp) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

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
                    </div>

                <?php } ?>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendAddEquip" class="btn-success" value="Cadastrar">Cadastrar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->