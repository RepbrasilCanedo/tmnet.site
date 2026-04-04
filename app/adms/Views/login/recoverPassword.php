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
                <h5>Recuperar Senha</h5>
                <p class="small  mt-2">Informe seu e-mail para receber o link.</p>
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
                    <i class="fa-solid fa-envelope icon-main"></i>
                    <input type="email" name="email" id="email" placeholder="Digite seu e-mail" value="<?php echo $valorForm['email'] ?? ''; ?>" required>
                </div>
                
                <button type="submit" name="SendRecover" value="Recuperar" class="btn-acessar">Enviar Link</button>
            </form>

            <div class="link-novo-login">
                <a href="<?php echo URLADM; ?>login/index"><i class="fa-solid fa-arrow-left"></i> Voltar para o Login</a>
            </div> 
            
            <div class="login-footer">
                <small>&copy; <?php echo date('Y'); ?> Rep Brasil Tecnologia</small>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
    var swiper = new Swiper('.promo-slider', {
        loop: true, autoplay: { delay: 5000, disableOnInteraction: false },
        effect: 'fade' // Efeito suave
    });
</script>