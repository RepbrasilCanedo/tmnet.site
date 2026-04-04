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
//echo "<pre>"; var_dump($valorForm);

?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Logo</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['view_profile']) {
                    echo "<a href='" . URLADM . "list-emp-principal/index' class='btn-primary'>Listar</a> ";
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
            <form method="POST" action="" id="form-edit-prof-logo" class="form-adm" enctype="multipart/form-data">

                <div class="row-input">

                    <div class="column">
                        <?php
                        $logo = "";
                        if (isset($valorForm['logo'])) {
                            $logo = $valorForm['logo'];
                        }
                        ?>
                        <label class="title-input"><b>OBS:</b><span class="text-danger"> Selecione uma imagem com extensão <b> " png ",  
                            tamanho 300 pixels x 300 pixels.</label>
                        <input type="file" name="new_image" id="new_image" class="input-adm" accept="image/png" onchange="inputFileValLogo()">
                    </div>

                    <div class="column">
                        <?php
                        if ((!empty($valorForm['logo'])) and (file_exists("app/adms/assets/image/logo/clientes/" . $valorForm['id'] . "/" . $valorForm['logo']))) {
                            
                            $old_image = URLADM . "app/adms/assets/image/logo/clientes/" . $valorForm['id'] . "/" . $valorForm['logo'];
                        } else {
                            $old_image = URLADM . "app/adms/assets/image/logo/clientes/icon_user.png";
                        }
                        ?>
                        <span id="preview-img">
                            <img src="<?php echo $old_image; ?>" alt="Imagem" style="width: 100px; height: 100px;">
                        </span>
                    </div>
                    
                </div>

                <p class="text-danger mb-5 fs-6">* Campo Obrigatório</p>

                <button type="submit" name="SendEditProfLogo" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->