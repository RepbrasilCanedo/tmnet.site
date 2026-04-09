<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
$valorForm = $this->data['form'] ?? [];
$clubes = $this->data['clubes'] ?? [];
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<div class="login-split-container" style="justify-content: center; background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; padding: 20px 0;">
    
    <div class="wrapper-login" style="max-width: 600px; width: 100%; margin: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-top: 5px solid #0044cc; background: #fff; padding: 30px; border-radius: 8px;">
        
        <div class="logo" style="text-align: center; margin-bottom: 20px;">
            <img src="<?php echo URLADM; ?>app/adms/assets/image/logo/logo.png" alt="TMNet Logo" style="max-width: 150px;">
        </div>

        <div class="title" style="text-align: center; margin-bottom: 25px;">
            <h5 style="margin-bottom: 5px; color: #333; font-size: 22px;">Credenciamento de Atleta</h5>
            <p style="font-size: 14px; color: #666; margin-top: 0;">Preencha os seus dados para solicitar a filiação.</p>
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
            
            <div class="login-input-group" style="margin-bottom: 15px;">
                <label style="font-size: 13px; font-weight: bold; color: #555; display:block; text-align: left; margin-bottom: 5px;">Selecione o Clube/Liga <span style="color:red;">*</span></label>
                <select name="empresa_id" class="input-adm" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;" required>
                    <option value="">-- Escolha o Clube que deseja representar --</option>
                    <?php foreach ($clubes as $clube): ?>
                        <option value="<?= $clube['id'] ?>" <?= (isset($valorForm['empresa_id']) && $valorForm['empresa_id'] == $clube['id']) ? 'selected' : '' ?>>
                            <?= $clube['nome_fantasia'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: bold; color: #0044cc; display:block; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">📄 Dados Pessoais</label>
                
                <div style="margin-bottom: 10px;">
                    <input type="text" name="name" placeholder="Nome Completo *" value="<?php echo $valorForm['name'] ?? ''; ?>" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                </div>

                <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <input type="text" name="apelido" placeholder="Apelido na Mesa *" value="<?php echo $valorForm['apelido'] ?? ''; ?>" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <input type="text" name="telefone" placeholder="WhatsApp / Telefone *" value="<?php echo $valorForm['telefone'] ?? ''; ?>" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="font-size: 12px; color: #666; display: block; margin-bottom: 2px;">Nascimento *</label>
                        <input type="date" name="data_nascimento" value="<?php echo $valorForm['data_nascimento'] ?? ''; ?>" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <label style="font-size: 12px; color: #666; display: block; margin-bottom: 2px;">Gênero *</label>
                        <select name="genero" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                            <option value="">Selecione...</option>
                            <option value="M" <?= (isset($valorForm['genero']) && $valorForm['genero'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                            <option value="F" <?= (isset($valorForm['genero']) && $valorForm['genero'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                <label style="font-size: 14px; font-weight: bold; color: #0044cc; display:block; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">🏓 Perfil Técnico</label>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <select name="estilo_jogo" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                            <option value="">Estilo de Jogo...</option>
                            <option value="Clássico" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Clássico') ? 'selected' : '' ?>>Clássico</option>
                            <option value="Caneteiro" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Caneteiro') ? 'selected' : '' ?>>Caneteiro</option>
                            <option value="Classineta" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Classineta') ? 'selected' : '' ?>>Classineta</option>
                            <option value="Pino Curto / Longo" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Pino Curto / Longo') ? 'selected' : '' ?>>Pino Curto / Longo</option>
                        </select>
                    </div>
                    <div style="flex: 1;">
                        <select name="mao_dominante" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                            <option value="">Mão Dominante...</option>
                            <option value="Destro" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Destro') ? 'selected' : '' ?>>Destro</option>
                            <option value="Canhoto" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Canhoto') ? 'selected' : '' ?>>Canhoto</option>
                            <option value="Ambidestro" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Ambidestro') ? 'selected' : '' ?>>Ambidestro</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <label style="font-size: 14px; font-weight: bold; color: #0044cc; display:block; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">🔐 Dados de Acesso</label>
                
                <div style="margin-bottom: 10px;">
                    <input type="email" name="email" placeholder="E-mail de Acesso *" value="<?php echo $valorForm['email'] ?? ''; ?>" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                </div>

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <input type="text" name="user" id="user" placeholder="Login (ex: joaosilva) *" value="<?php echo $valorForm['user'] ?? ''; ?>" required autocapitalize="none" style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                    </div>
                    <div style="flex: 1;">
                        <input type="password" name="password" placeholder="Senha Forte *" required style="width: 100%; border: 1px solid #ccc; padding: 10px; border-radius: 4px;">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div class="g-recaptcha" data-sitekey="6LckDq0sAAAAADjSgFKX_9FFnEGbIqJVW8ncKBAm"></div>
            </div>

            <button type="submit" name="SendNovaConta" value="Cadastrar" class="btn-acessar" style="width: 100%; background: #28a745; border: none; color: white; font-weight: bold; padding: 15px; border-radius: 4px; cursor: pointer; font-size: 16px; text-transform: uppercase;">Enviar Pedido</button>
        </form>

        <div class="link-novo-login" style="margin-top: 20px; text-align: center;">
            <a href="<?php echo URLADM; ?>login/index" style="color: #666; text-decoration: none; font-weight: bold;">⬅️ Voltar para o Login</a>
        </div> 
    </div>

</div>

<script>
    // Força o login a ficar em minúsculas e sem espaços
    document.addEventListener("DOMContentLoaded", function() {
        const inputUser = document.getElementById('user');
        if(inputUser) {
            inputUser.addEventListener('input', function() {
                this.value = this.value.toLowerCase().replace(/\s+/g, '');
            });
        }
    });
</script>