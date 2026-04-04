<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Perfil</span>
            <div class="top-list-right">
                <?php
                if (!empty($this->data['viewProfile'])) {
                    if ($this->data['button']['edit_profile']) {
                        echo "<a href='" . URLADM . "edit-profile/index' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['edit_profile_password']) {
                        echo "<a href='" . URLADM . "edit-profile-password/index' class='btn-warning'>Editar Senha</a> ";
                    }
                    if ($this->data['button']['edit_profile_image']) {
                        echo "<a href='" . URLADM . "edit-profile-image/index' class='btn-warning'>Editar Imagem</a> ";
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
        </div>

        <div class="content-adm">
            <?php
            if (!empty($this->data['viewProfile'])) {
                extract($this->data['viewProfile'][0]);
            ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">Foto: </span>
                    <span class="view-adm-info">
                        <?php
                        if ((!empty($imagem)) and (file_exists("app/adms/assets/image/users/" . $_SESSION['user_id'] . "/$imagem"))) {
                            echo "<img src='" . URLADM . "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/$imagem' width='100' height='100'><br><br>";
                        } else {
                            echo "<img src='" . URLADM . "app/adms/assets/image/users/icon_user.png' width='100' height='100'><br><br>";
                        }
                        ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo $name; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Apelido: </span>
                    <span class="view-adm-info"><?php echo $apelido; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">E-mail: </span>
                    <span class="view-adm-info"><?php echo $email; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Telefone Principal: </span>
                    <span class="view-adm-info"><?php echo $tel_1; ?></span>
                </div>
                <?php if ($_SESSION['adms_access_level_id'] <> 14) {?>
                    <div class="view-det-adm">
                    <span class="view-adm-title">Telefone Secundário: </span>
                    <span class="view-adm-info"><?php echo $tel_2; ?></span>
                </div>
                <?php } ?>


                
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->