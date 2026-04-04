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
            <span class="title-content">Editar Perfil</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['view_profile']) {
                    echo "<a href='" . URLADM . "view-profile/index' class='btn-primary'>Perfil</a> ";
                }
                ?>
            </div>
        </div>

        <div class="content-adm">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <span id="msg"></span>
        </div>

        <div class="content-adm-alert">
            <form method="POST" action="" id="form-edit-profile" class="form-adm">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome completo" value="<?php echo $name; ?>" required>
                    </div>
                    <div class="column">
                        <?php
                        $apelido = "";
                        if (isset($valorForm['apelido'])) {
                            $apelido = $valorForm['apelido'];
                        }
                        ?>
                        <label class="title-input">Apelido:</label>
                        <input type="text" name="apelido" id="apelido" class="input-adm" placeholder="Digite o apelido" value="<?php echo $apelido; ?>">
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php
                        $email = "";
                        if (isset($valorForm['email'])) {
                            $email = $valorForm['email'];
                        }
                        ?>
                        <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="input-adm" placeholder="Digite o seu melhor e-mail" value="<?php echo $email; ?>" required>

                    </div>
                    <div class="column">
                        <?php
                        $user = "";
                        if (isset($valorForm['user'])) {
                            $user = $valorForm['user'];
                        }
                        ?>
                        <label class="title-input">Usuário:<span class="text-danger">*</span></label>
                        <input type="text" name="user" id="user" class="input-adm" placeholder="Digite o usuário para acessar o administrativo" value="<?php echo $user; ?>" required>

                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php
                        $tel_1 = "";
                        if (isset($valorForm['tel_1'])) {
                            $tel_1 = $valorForm['tel_1'];
                        }
                        ?>
                        <label class="title-input">Telefone Principal: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_1" id="tel_1" class="input-adm" placeholder="Digite o telefone principal" value="<?php echo $tel_1; ?> " required>
                    </div>
                    <?php if ($_SESSION['adms_access_level_id'] <> 14) { ?>
                    <div class="column">
                        <?php
                        $tel_2 = "";
                        if (isset($valorForm['tel_2'])) {
                            $tel_2 = $valorForm['tel_2'];
                        }
                        ?>
                        <label class="title-input">Telefone Secundário: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_2" id="tel_2" class="input-adm" placeholder="Digite o telefone" value="<?php echo $tel_2; ?>" required>
                    </div>
                    <?php } ?>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditProfile" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->