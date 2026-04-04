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
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Produto do Cliente:</span>
            <span class="title-content"><?php echo $this->data['form'][0]['razao_social_clie']; ?></span>
            <span class="title-content">--</span>
            <span class="title-content"><?php echo $this->data['form'][0]['nome_fantasia_clie']; ?></span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_prod']) {
                    echo "<a href='" . URLADM . "list-prod/index' class='btn-info'>Listar</a> ";
                }
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_prod']) {
                        echo "<a href='" . URLADM . "view-prod/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
            <form method="POST" action="" id="form-edit-prod" class="form-adm">
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
                        $name_prod = "";
                        if (isset($valorForm['name_prod'])) {
                            $name_prod = $valorForm['name_prod'];
                        }
                        ?>
                        <label class="title-input">Nome do Produto:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome do produto" value="<?php echo $name_prod; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Tipo:<span class="text-danger">*</span></label>
                        <select name="type_id" id="type_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['type_prod'] as $name_type) {
                                extract($name_type);
                                if (isset($valorForm['name_type']) and $valorForm['name_type'] == $name_typ) {
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
                        $serie_prod = "";
                        if (isset($valorForm['serie_prod'])) {
                            $serie_prod = $valorForm['serie_prod'];
                        }
                        ?>
                        <label class="title-input">Nº Série:<span class="text-danger">*</span></label>
                        <input type="text" name="serie" id="serie" class="input-adm" placeholder="Digite o numero de série do produto" value="<?php echo $serie_prod; ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">            
                            <?php
                            $name_modelo = "";
                            if (isset($valorForm['name_modelo'])) {
                                $name_modelo = $valorForm['name_modelo'];
                            }
                            ?>
                            <label class="title-input">Modelo:<span class="text-danger">*</span></label>
                            <input type="text" name="modelo_id" id="modelo_id" class="input-adm" placeholder="Digite o numero de série do produto" value="<?php echo $name_modelo; ?>" required>
                    </div>
                    <div class="column">            
                            <?php
                            $name_mar = "";
                            if (isset($valorForm['name_mar'])) {
                                $name_mar = $valorForm['name_mar'];
                            }
                            ?>
                            <label class="title-input">Marca:<span class="text-danger">*</span></label>
                            <input type="text" name="marca_id" id="marca_id" class="input-adm" placeholder="Digite a marca do produto" value="<?php echo $name_mar; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Situação:<span class="text-danger">*</span></label>
                        <select name="sit_id" id="sit_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['sit_prod'] as $sitProd) {
                                extract($sitProd);
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

                <div class="row-input">                        
                        <div class="column">
                            <label class="title-input">Tipo do Contrato:<span class="text-danger">*</span></label>
                            <select name="contr_id" id="contr_id" class="input-adm" required>
                                <?php
                                foreach ($this->data['select']['contr_id'] as $contr_id) {
                                    extract($contr_id);
                                    if (isset($valorForm['name_contr_id']) and $valorForm['name_contr_id'] == $name) {
                                        echo "<option value='$id' selected>$name</option>";
                                    } else {
                                        echo "<option value='$id'>$name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="column">
                            <?php
                            $venc_contr_prod = "";
                            if (isset($valorForm['venc_contr_prod'])) {
                                $venc_contr_prod = $valorForm['venc_contr_prod'];
                            }
                            ?>
                            <label class="title-input">Vencimento Contrato:<span class="text-danger">*</span></label>
                            <input type=date name="venc_contr" id="venc_contr" class="input-adm" placeholder="Observações" value="<?php echo $venc_contr_prod; ?>" required>
                        </div>
                </div>
                <div class="row-input">                    
                    <div class="column">            
                            <?php
                            $inf_adicionais = "";
                            if (isset($valorForm['inf_adicionais'])) {
                                $inf_adicionais = $valorForm['inf_adicionais'];
                            }
                            ?>
                            <label class="title-input">Informações Adicionais:<span class="text-danger">*</span></label>
                            <textarea name="inf_adicionais" id="inf_adicionais" class="input-adm" placeholder="Observações" value="" required><?php echo $inf_adicionais; ?></textarea>
                    </div>
                </div>

                <p class="text-danger mb-3 fs-6">* Campo Obrigatório</p>

                <button type="submit" name="SendEditProd" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->