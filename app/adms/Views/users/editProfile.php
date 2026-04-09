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
            <span class="title-content">Editar Perfil</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['view_profile']) {
                    echo "<a href='" . URLADM . "view-profile/index' class='btn-primary'>Voltar ao Perfil</a> ";
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
                        <label class="title-input">Usuário (Acesso):<span class="text-danger">*</span></label>
                        <input type="text" name="user" id="user" class="input-adm" placeholder="Digite o usuário para acessar o sistema" value="<?php echo $user; ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php
                        $telefone = "";
                        if (isset($valorForm['telefone'])) {
                            $telefone = trim($valorForm['telefone']); // Remove os espaços sobrando
                        }
                        ?>
                        <label class="title-input">Tel/WhatsApp: <span class="text-danger">*</span></label>
                        <input type="text" name="telefone" id="telefone" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?php echo $telefone; ?>" required>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4" style="margin-top: 15px;">* Campo Obrigatório</p>

                <button type="submit" name="SendEditProfile" class="btn-warning" value="Salvar" style="margin-top: 10px; width: 100%; max-width: 200px;">💾 Salvar Alterações</button>

            </form>
        </div>
    </div>
</div>

<script>
    // DOCAN FIX: Máscara para manter o telefone formatado bonitinho!
    function mascaraTelefone(t) {
        let v = t.value;
        v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); // Coloca parênteses em volta dos dois primeiros dígitos
        
        if (v.length > 13) {
            v = v.replace(/(\d{5})(\d)/, "$1-$2"); // Número de telemóvel
        } else {
            v = v.replace(/(\d{4})(\d)/, "$1-$2"); // Número fixo
        }
        t.value = v;
    }
</script>