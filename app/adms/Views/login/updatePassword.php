<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
?>
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<div class="login-split-container">
    <div class="login-side-promo">
        <div class="swiper-container promo-slider">
            <div class="swiper-wrapper">
                 <div class="swiper-slide" style="background-image: url('<?php echo URLADM; ?>app/adms/assets/image/promo/promo3.jpeg');"></div>
            </div>
        </div>
    </div>

    <div class="login-side-form">
        <div class="wrapper-login">
            <div class="logo">
                <img src="<?php echo URLADM; ?>app/adms/assets/image/logo/logo.png" alt="Docnet">
            </div>

            <div class="title">
                <h5>Nova Senha</h5>
                <p class="small text-muted mt-2">Crie uma nova senha segura.</p>
            </div>

            <div class="msg-alert">
                <?php
                if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
                ?>
            </div>

            <form method="POST" action="" class="form-login">
                <div class="login-input-group">
                    <i class="fa-solid fa-lock icon-main"></i>
                    <input type="password" name="password" id="password" placeholder="Nova Senha" required>
                    <i class="fa-solid fa-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
                
                <button type="submit" name="SendUpPass" value="Salvar" class="btn-acessar">Salvar Nova Senha</button>
            </form>

            <div class="link-novo-login">
                <a href="<?php echo URLADM; ?>login/index">Cancelar</a>
            </div> 
            
            <div class="login-footer">
                <small>&copy; <?php echo date('Y'); ?> Rep Brasil Tecnologia</small>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>