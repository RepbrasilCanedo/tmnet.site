<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

//var_dump($_SESSION['set_Contr_emp']);
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Setor da Empresa</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_setor']) {
                    echo "<a href='" . URLADM . "list-setor/index' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-add-setor" class="form-adm">

                <div class="row-input">

                    <?php if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) { ?>
                        
                        <div class="column">
                            <label class="title-input">Empresa do Setor:<span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_setor'] as $empSetor) {
                                    extract($empSetor);

                                    if (isset($valorForm['empresa_id']) and $valorForm['empresa_id'] == $id_emp) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp </option>";
                                    }
                                }

                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <?php
                            $name = "";
                            if (isset($valorForm['name'])) {
                                $name = $valorForm['name'];
                            }
                            ?>
                            <label class="title-input">Nome:<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome do setor" value="<?php echo $name; ?>" required>
                        </div>

                    <?php } else { ?>
                        
                        <div class="column">
                            <label class="title-input">Contratos:<span class="text-danger">*</span></label>
                            <select name="cont_id" id="cont_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_cont'] as $empSetor) {
                                    extract($empSetor);

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
                            <label class="title-input">Empresa do Setor:<span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp_setor'] as $empSetor) {
                                    extract($empSetor);

                                    if (isset($valorForm['empresa_id']) and $valorForm['empresa_id'] == $id_emp) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp </option>";
                                    }
                                }

                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <?php
                            $name = "";
                            if (isset($valorForm['name'])) {
                                $name = $valorForm['name'];
                            }
                            ?>
                            <label class="title-input">Nome:<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome do setor" value="<?php echo $name; ?>" required>
                        </div>
                    <?php } ?>

                </div>


                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendAddSetor" class="btn-success" value="Cadastrar">Cadastrar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->