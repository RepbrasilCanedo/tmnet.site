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
<div class="wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Senha</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_conf_emails']) {
                    echo "<a href='" . URLADM . "list-conf-emails/index' class='btn-info'>Listar</a> ";
                }                
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_conf_emails']) {
                        echo "<a href='" . URLADM . "view-conf-emails/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a> ";
                    }
                    if ($this->data['button']['edit_conf_emails']) {
                        echo "<a href='" . URLADM . "edit-conf-emails/index/" . $valorForm['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['delete_conf_emails']) {
                        echo "<a href='" . URLADM . "delete-conf-emails/index/" . $valorForm['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
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
            <form method="POST" action="" id="form-edit-conf-emails-pass" class="form-adm">
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
                        $password = "";
                        if (isset($valorForm['password'])) {
                            $password = $valorForm['password'];
                        }
                        ?>
                        <label class="title-input">Senha:<span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="input-adm" placeholder="Senha do e-mail" autocomplete="on" value="<?php echo $password; ?>" >

                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditConfEmailsPass" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->