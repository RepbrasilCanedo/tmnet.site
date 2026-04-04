<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

if (isset($this->data['form'][0])) {
    $valorForm = $this->data['form'][0];
}

//var_dump($valorForm);

?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Equipamentos</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_equip']) {
                    echo "<a href='" . URLADM . "list-equip/index' class='btn-info'>Listar</a> ";
                }
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_equip']) {
                        echo "<a href='" . URLADM . "view-equip/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
                    }
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
            <form method="POST" action="" id="form-edit-equip" class="form-adm">
                <?php
                $id = "";
                if (isset($valorForm['id'])) {
                    $id = $valorForm['id'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Nome do Equipamento:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome do equipaqmento" value="<?php echo $name; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Tipo:<span class="text-danger">*</span></label>
                        <select name="type_id" id="type_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['type_equip'] as $typeEquip) {
                                extract($typeEquip);
                                if (isset($valorForm['name_typ']) and $valorForm['name_typ'] == $name_typ) {
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
                        <label class="title-input">Nº Série:<span class="text-danger">*</span></label>
                        <input type="text" name="serie" id="serie" class="input-adm" placeholder="Digite o numero de série do equipqmento" value="<?php echo $serie; ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">

                        <label class="title-input">Modelo:<span class="text-danger">*</span></label>
                        <select name="modelo_id" id="modelo_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['mod_equip'] as $modEquip) {
                                extract($modEquip);
                                if (isset($valorForm['name_modelo']) and $valorForm['name_modelo'] == $name_modelo) {
                                    echo "<option value='$id_modelo' selected>$name_modelo</option>";
                                } else {
                                    echo "<option value='$id_modelo'>$name_modelo</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Marca:<span class="text-danger">*</span></label>
                        <select name="marca_id" id="marca_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['marca_equip'] as $marcaEquip) {
                                extract($marcaEquip);
                                if (isset($valorForm['name_mar']) and $valorForm['name_mar'] == $name_mar) {
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
                        <label class="title-input">Empresa:<span class="text-danger">*</span></label>
                        <select name="empresa_id" id="empresa_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['emp_equip'] as $empEquip) {
                                extract($empEquip);
                                if (isset($valorForm['nome_fantasia_emp']) and $valorForm['nome_fantasia_emp'] == $nome_fantasia_emp) {
                                    echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                } else {
                                    echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>


                    <?php if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) { ?>
                    
                    <?php } else { ?>
                        <div class="column">
                            <label class="title-input">Contratos:<span class="text-danger">*</span></label>
                            <select name="cont_id" id="cont_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_cont'] as $empCont) {
                                    extract($empCont);

                                    if (isset($valorForm['num_cont_equip']) and $valorForm['num_cont_equip'] == $cont_id) {
                                        echo "<option value='$id_cont' selected>$cont_id</option>";
                                    } else {
                                        echo "<option value='$id_cont'>$cont_id</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php } ?>

                    
                    <div class="column">
                        <label class="title-input">Situação:<span class="text-danger">*</span></label>
                        <select name="sit_id" id="sit_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['sit_equip'] as $sitEquip) {
                                extract($sitEquip);
                                if (isset($valorForm['name_sit']) and $valorForm['name_sit'] == $name_sit) {
                                    echo "<option value='$id_sit' selected>$name_sit</option>";
                                } else {
                                    echo "<option value='$id_sit'>$name_sit</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditEquip" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->