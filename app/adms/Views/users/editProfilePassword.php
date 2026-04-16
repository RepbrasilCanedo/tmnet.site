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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos flexíveis para o telemóvel */
    .row-password-group { display: flex; gap: 15px; flex-wrap: wrap; }
    .row-password-group > .column { flex: 1; min-width: 250px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Alterar Minha Senha</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['view_profile']) {
                    echo "<a href='" . URLADM . "view-profile/index' class='btn-primary' style='background-color: #6c757d; border: none;'>⬅️ Voltar ao Perfil</a> ";
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
            <form method="POST" action="" id="form-edit-prof-pass" class="form-adm">

                <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #856404; margin-top: 0; border-bottom: 1px solid #ffeeba; padding-bottom: 8px; margin-bottom: 15px;">🔐 Credenciais de Acesso</h4>
                    
                    <div class="row-password-group">
                        <div class="column">
                            <label class="title-input">Nova Senha:<span class="text-danger">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="password" id="password" class="input-adm" placeholder="Digite a nova senha" onkeyup="passwordStrength()" autocomplete="off" required style="width: 100%; padding-right: 45px; border-radius: 4px;">
                                <button type="button" onclick="togglePass('password', 'eye1')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #666; font-size: 16px; padding: 5px;">
                                    <i class="fa-regular fa-eye" id="eye1"></i>
                                </button>
                            </div>
                            <span id="msgViewStrength" style="display: block; margin-top: 5px; font-size: 12px;"></span>
                        </div>

                        <div class="column">
                            <label class="title-input">Confirmar Nova Senha:<span class="text-danger">*</span></label>
                            <div style="position: relative;">
                                <input type="password" name="conf_password" id="conf_password" class="input-adm" placeholder="Repita a nova senha" autocomplete="off" required style="width: 100%; padding-right: 45px; border-radius: 4px;">
                                <button type="button" onclick="togglePass('conf_password', 'eye2')" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #666; font-size: 16px; padding: 5px;">
                                    <i class="fa-regular fa-eye" id="eye2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4" style="margin-bottom: 15px;">* Campos Obrigatórios</p>

                <button type="submit" name="SendEditProfPass" class="btn-warning" value="Salvar" style="width: 100%; max-width: 250px; padding: 14px; font-size: 16px; font-weight: bold; background-color: #ffc107; border: none; border-radius: 6px; color: #212529; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    Atualizar Senha
                </button>

            </form>
        </div>
    </div>
</div>

<script>
    // DOCAN FIX: Função para mostrar/esconder a senha em qualquer campo!
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>