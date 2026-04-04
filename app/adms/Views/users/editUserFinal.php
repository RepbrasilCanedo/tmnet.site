<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$valorForm = $this->data['form'][0] ?? ($this->data['form'] ?? []);

/**
 * Função old Segura
 * @param string $dbKey Chave do banco
 * @param string|null $postKey Chave do formulário
 * @param array $source Onde buscar os dados (variável $valorForm)
 */
function old(string $dbKey, ?string $postKey = null, array $source = []) {
    $key = $postKey ?? $dbKey;

    // 1. Tenta no POST (erro de formulário)
    if (isset($source[$key])) {
        return $source[$key];
    }
    // 2. Tenta no Banco (carregamento inicial)
    return $source[$dbKey] ?? "";
}
//echo "<pre>";
//print_r($this->data['form']);
//echo "</pre>";
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Usuário Final</span>
            <div class="top-list-right">
                <?php if ($this->data['button']['list_users_final']): ?>
                    <a href="<?= URLADM ?>list-users-final/index" class="btn-info">Listar</a>
                <?php endif; ?>
                
                <?php if (old('id') && $this->data['button']['view_users_final']): ?>
                    <a href="<?= URLADM ?>view-users-final/index/<?= old('id') ?>" class="btn-primary">Visualizar</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-adm-alert">
            <?= $_SESSION['msg'] ?? "" ?>
            <?php unset($_SESSION['msg']); ?>
            <span id="msg"></span>
        </div>

        <div class="content-adm">
            <form method="POST" action="" id="form-edit-user" class="form-adm">
                <input type="hidden" name="id" value="<?= old('id', 'id', $valorForm) ?>">

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="input-adm" value="<?= old('name_usr_final', 'name', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Tel/WhatsApp:<span class="text-danger">*</span></label>
                        <input type="text" name="tel_1" class="input-adm" oninput="mascaraTelefone(this)" value="<?= old('tel_1_usr_final', 'tel_1', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="input-adm" value="<?= old('email_usr_final', 'email', $valorForm) ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Empresa: <span class="text-danger">*</span></label>
                        <select name="cliente_id" id="cliente_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['select']['emp'] as $emp): extract($emp); ?>
                                <?php 
                                    // Verifica se este item é o que deve estar selecionado
                                    // Tenta primeiro o valor do banco, depois o valor que o usuário pode ter tentado enviar via POST
                                    $selected = (
                                        $id_emp == old('empresa_id_usr_final', 'cliente_id', $valorForm)
                                    ) ? 'selected' : ''; 
                                ?>
                                <option value="<?= $id_emp ?>" <?= $selected ?>>
                                    <?= $nome_fantasia_emp ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Usuário:<span class="text-danger">*</span></label>
                        <input type="text" name="user" class="input-adm" value="<?= old('user_usr_final', 'user', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Situação:<span class="text-danger">*</span></label>
                        <select name="adms_sits_user_id" id="adms_sits_user_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['select']['sit_user'] as $sit): extract($sit); ?>
                                <?php 
                                    // Agora comparamos o ID que acabamos de adicionar na Model
                                    $valBanco = old('adms_sits_user_id', 'adms_sits_user_id', $valorForm);
                                    $selected = ($id_sit == $valBanco) ? 'selected' : ''; 
                                ?>
                                <option value="<?= $id_sit ?>" <?= $selected ?>>
                                    <?= $name_sit ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Senha:</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" name="password" id="password" class="input-adm" placeholder="Deixe em branco para não alterar" onkeyup="passwordStrength()" autocomplete="new-password" style="padding-right: 40px;">
                            
                            <span id="togglePassword" onclick="togglePasswordVisibility()" style="position: absolute; right: 10px; cursor: pointer; color: #666;">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        <small>Apenas preencha se desejar alterar a senha atual.</small>
                        <span id="msgViewStrength"></span>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>
                <button type="submit" name="SendEditUserFinal" class="btn-warning" value="Salvar">Salvar Alterações</button>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordField.type = "password";
            eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    function mascaraTelefone(t) {
        let v = t.value.replace(/\D/g, "");
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        if (v.length > 13) v = v.replace(/(\d{5})(\d)/, "$1-$2");
        else v = v.replace(/(\d{4})(\d)/, "$1-$2");
        t.value = v;
    }
</script>