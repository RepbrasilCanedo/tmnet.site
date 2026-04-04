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
            <span class="title-content">Editar Imagem Usuário Final</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users_final']) {
                    echo "<a href='" . URLADM . "list-users-final/index' class='btn-info'>Listar</a> ";
                }                
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_users_final']) {
                        echo "<a href='" . URLADM . "view-users-final/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
            <form method="POST" action="" id="form-edit-user-img-clie-fin" class="form-adm" enctype="multipart/form-data">
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
                        <label class="title-input">Imagem:<span class="text-danger">*</span> 300x300</label>
                        <input type="file" name="new_image_clie_fin" id="new_image_clie_fin" class="input-adm" onchange="inputFileValImgClieFin()">
                    </div>
                    <div class="column">
                        <?php
                        if ((!empty($valorForm['image'])) and (file_exists("app/adms/assets/image/usersfinal/" . $valorForm['id'] . "/" . $valorForm['image']))) {
                            $old_image = URLADM . "app/adms/assets/image/usersfinal/" . $valorForm['id'] . "/" . $valorForm['image'];
                        } else {
                            $old_image = URLADM . "app/adms/assets/image/usersfinal/icon_user.png";
                        }
                        ?>
                        <span id="preview-img-avatar-clie-fin">
                            <img src="<?php echo $old_image; ?>" alt="Imagem" style="width: 100px; height: 100px;">
                        </span>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditUserImageFinal" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->