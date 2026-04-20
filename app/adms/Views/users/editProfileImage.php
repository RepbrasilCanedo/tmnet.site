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
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Foto</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['view_profile']) {
                    echo "<a href='" . URLADM . "view-profile/index' class='btn-primary'>Perfil</a> ";
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
            <form method="POST" action="" id="form-edit-prof-contr" class="form-adm" enctype="multipart/form-data">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Imagem do Perfil:<span class="text-danger">*</span> (Tire uma foto ou escolha da galeria)</label>
                        
                        <input type="file" name="new_image" id="new_image" class="input-adm" accept="image/*" capture="user" onchange="inputFileValImg()">
                    </div>
                    <div class="column">
                        <?php
                        if ((!empty($valorForm['image'])) and (file_exists("app/adms/assets/image/users/" . $_SESSION['user_id'] . "/" . $valorForm['image']))) {
                            $old_image = URLADM . "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/" . $valorForm['image'];
                        } else {
                            $old_image = URLADM . "app/adms/assets/image/users/icon_user.png";
                        }
                        ?>
                        <span id="preview-img-avatar">
                            <img src="<?php echo $old_image; ?>" alt="Imagem" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        </span>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditProfImage" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>