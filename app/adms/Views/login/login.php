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
                    <input type="text" name="user" id="user" placeholder="Usuário" value="<?php echo $valorForm['user'] ?? ''; ?>" required autofocus autocapitalize="none">
                </div>

                <div class="login-input-group" style="margin-bottom: 5px;">
                    <i class="fa-solid fa-lock icon-main"></i>
                    <input type="password" name="password" id="password" placeholder="Senha" autocomplete="current-password" required>
                    <i class="fa-solid fa-eye-slash" id="togglePassword" onclick="togglePasswordVisibility()"></i>
                </div>
                
                <div id="caps-warning" style="display: none; color: #dc3545; font-size: 12px; font-weight: bold; margin-bottom: 15px; padding-left: 5px; text-align: left;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Aviso: Tecla Caps Lock ativada!
                </div>
                
                <button type="submit" name="SendLogin" value="Acessar" class="btn-acessar" style="margin-top: 10px;">Acessar Sistema</button>
            </form>

            <div class="link-novo-login">
                <a href="<?php echo URLADM; ?>recover-password/index">Esqueci a Senha </a>
            </div> 
            <div class="link-novo-login">
                <a href="<?= URLADM ?>novo-credenciamento/index"> Quero ser um Atleta!</a>
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

        // Força a conversão para minúsculas no usuário em tempo real
        const inputUser = document.getElementById('user');
        if(inputUser) {
            inputUser.addEventListener('input', function() {
                this.value = this.value.toLowerCase();
            });
        }

        // DOCAN: Detetor de Caps Lock na Senha
        const passwordInput = document.getElementById('password');
        const capsWarning = document.getElementById('caps-warning');

        if (passwordInput && capsWarning) {
            // Verifica enquanto o usuário digita
            passwordInput.addEventListener('keyup', function(event) {
                if (event.getModifierState('CapsLock')) {
                    capsWarning.style.display = 'block';
                } else {
                    capsWarning.style.display = 'none';
                }
            });
            
            // Verifica caso ele clique no campo com o Caps Lock já ligado
            passwordInput.addEventListener('mousedown', function(event) {
                if (event.getModifierState('CapsLock')) {
                    capsWarning.style.display = 'block';
                } else {
                    capsWarning.style.display = 'none';
                }
            });
        }

        function togglePasswordVisibility() {
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