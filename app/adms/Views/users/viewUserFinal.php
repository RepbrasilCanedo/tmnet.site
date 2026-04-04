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
            <span class="title-content">Detalhes do Usuário</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users_final']) {
                    echo "<a href='" . URLADM . "list-users-final/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewUserFinal'])) {
                    if ($this->data['button']['edit_users_final']) {
                        echo "<a href='" . URLADM . "edit-users-final/index/" . $this->data['viewUserFinal'][0]['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['edit_users_image_final']) {
                        echo "<a href='" . URLADM . "edit-users-image-final/index/" . $this->data['viewUserFinal'][0]['id'] . "' class='btn-warning'>Editar Imagem</a> ";
                    }
                    if ($this->data['button']['delete_users_final']) {
                        echo "<a href='" . URLADM . "delete-users-final/index/" . $this->data['viewUserFinal'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
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
            if (!empty($this->data['viewUserFinal'])) {
                extract($this->data['viewUserFinal'][0]);
            ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">Foto: </span>
                    <span class="view-adm-info">
                        <?php
                        if ((!empty($image)) and (file_exists("app/adms/assets/image/usersfinal/$id/$image"))) {
                            echo "<img src='" . URLADM . "app/adms/assets/image/usersfinal/$id/$image' width='100' height='100'><br><br>";
                        } else {
                            echo "<img src='" . URLADM . "app/adms/assets/image/usersfinal/icon_user.png' width='100' height='100'><br><br>";
                        }
                        ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo $name_usr_final; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">E-mail: </span>
                    <span class="view-adm-info"><?php echo $email_usr_final; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Tel/WhatsApp: </span>
                    <span class="view-adm-info"><?php echo $tel_1_usr_final; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Usuário: </span>
                    <span class="view-adm-info"><?php echo $user_usr_final; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação do Usuário: </span>
                    <span class="view-adm-info">
                        <?php echo "<span style='color: $color_col;'>$name_sit</span>"; ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nível de Acesso: </span>
                    <span class="view-adm-info"><?php echo  $name_lev; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Empresa: </span>
                    <span class="view-adm-info"><?php echo  $razao_social_emp . " -- " . $nome_fantasia_clie; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created_usr_final)); ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Editado: </span>
                    <span class="view-adm-info">
                        <?php
                        if (!empty($modified)) {
                            echo date('d/m/Y H:i:s', strtotime($modified_usr_final));
                        } ?>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->