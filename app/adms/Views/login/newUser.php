<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$valorForm = $this->data['form'] ?? [];
?>

<div class="login-main-flex">
    <div class="wrapper-login">
        <div class="logo">
            <img src="<?php echo URLADM; ?>app/adms/assets/image/logo/logo.png" alt="Docnet">
        </div>

        <div class="title" style="height: auto; padding: 15px;">
            <h5 style="font-size: 0.95rem; line-height: 1.4;">Solicitar Nova Senha</h5>
        </div>

        <div class="msg-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
            <p class="text-muted text-center" style="font-size: 0.8rem; margin-top: 10px;">
                Informe seu usuário e telefone. Nossa equipe entrará em contato.
            </p>
        </div>

        <form method="POST" action="" id="form-new-user" class="form-login">
            
            <div class="login-input-group">
                <i class="fa-solid fa-user icon-main"></i>
                <input type="text" name="email" id="email" placeholder="Usuário cadastrado" value="<?php echo $valorForm['email'] ?? ''; ?>" required>
            </div>

            <div class="login-input-group">
                <i class="fa-brands fa-whatsapp icon-main"></i>
                <input type="tel" name="tel" id="tel" autocomplete="tel" placeholder="WhatsApp de contato" value="<?php echo $valorForm['tel'] ?? ''; ?>" required>
            </div>

            <button type="submit" name="SendNewUser" value="Cadastrar" class="btn-acessar">Solicitar Contato</button>
        </form>

        <div class="link-novo-login">
            <a href="<?php echo URLADM; ?>login/index">Voltar para o Login</a>
        </div>              
    </div>
</div>