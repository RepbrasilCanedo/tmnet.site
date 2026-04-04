<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
$valorForm = $this->data['form'] ?? [];
?>

<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

<div class="login-split-container">


    <div class="login-side-form">
        <div class="wrapper-login">
            <div class="logo">
                <img src="<?php echo URLADM; ?>app/adms/assets/image/logo/logo.png" alt="Docnet">
            </div>

            <div class="title">
                <h5>TmNet / Acesso Restrito</h5>
            </div>

            <div class="msg-alert">
                <?php
                if (isset($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
                ?>
            </div>

            <form method="POST" action="" id="form-login" class="form-login">
                <div class="login-input-group">
                    <i class="fa-solid fa-user icon-main"></i>
                    <input type="text" name="user" id="user" placeholder="Usuário" value="<?php echo $valorForm['user'] ?? ''; ?>" required>
                </div>

                <div class="login-input-group">
                    <i class="fa-solid fa-lock icon-main"></i>
                    <input type="password" name="password" id="password" placeholder="Senha" autocomplete="current-password" required>
                    <i class="fa-solid fa-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
                
                <button type="submit" name="SendLogin" value="Acessar" class="btn-acessar">Acessar Sistema</button>
            </form>

            <div class="link-novo-login">
                <a href="<?php echo URLADM; ?>recover-password/index">Esqueci a Senha</a>
            </div> 
            
            <div class="login-footer">
                <small>&copy; <?php echo date('Y'); ?> Rep Brasil Tecnologia</small>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var swiper = new Swiper('.promo-slider', {
            loop: true, autoplay: { delay: 10000, disableOnInteraction: false },
            pagination: { el: '.swiper-pagination', clickable: true },
        });

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
        window.togglePasswordVisibility = togglePasswordVisibility;
    });
</script>