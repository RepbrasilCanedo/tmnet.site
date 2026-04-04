<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$valorForm = $this->data['form'] ?? [];

/**
 * Função old Segura para Cadastro
 */
function old(string $key, array $source = []) {
    return $source[$key] ?? "";
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Usuário Final</span>
            <div class="top-list-right">
                <?php if ($this->data['button']['list_users_final']): ?>
                    <a href="<?= URLADM ?>list-users-final/index" class="btn-info">Listar</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="content-adm-alert">
            <?= $_SESSION['msg'] ?? "" ?>
            <?php unset($_SESSION['msg']); ?>
            <span id="msg"></span>
        </div>

        <div class="content-adm">
            <form method="POST" action="" id="form-add-user-final" class="form-adm">
                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="input-adm" placeholder="Nome completo" value="<?= old('name', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                        <input type="email" name="email" class="input-adm" placeholder="E-mail" value="<?= old('email', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Tel/WhatsApp:<span class="text-danger">*</span></label>
                        <input type="text" name="tel_1" class="input-adm" maxlength="15" oninput="mascaraTelefone(this)" placeholder="(00) 00000-0000" value="<?= old('tel_1', $valorForm) ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cliente: <span class="text-danger">*</span></label>
                        <select name="cliente_id" id="cliente_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['select']['emp'] as $emp): extract($emp); ?>
                                <option value="<?= $id_emp ?>" <?= (old('cliente_id', $valorForm) == $id_emp) ? 'selected' : '' ?>>
                                    <?= $nome_fantasia_emp ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Situação:<span class="text-danger">*</span></label>
                        <select name="adms_sits_user_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['select']['sit'] as $sit): extract($sit); ?>
                                <option value="<?= $id_sit ?>" <?= (old('adms_sits_user_id', $valorForm) == $id_sit) ? 'selected' : '' ?>>
                                    <?= $name_sit ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Usuário:<span class="text-danger">*</span></label>
                        <input type="text" name="user" class="input-adm" placeholder="Usuário para acesso" value="<?= old('user', $valorForm) ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Senha:<span class="text-danger">*</span></label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" name="password" id="password" class="input-adm" onkeyup="passwordStrength()" autocomplete="new-password" style="padding-right: 40px;" required>
                            <span onclick="togglePasswordVisibility()" style="position: absolute; right: 10px; cursor: pointer; color: #666;">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                        <span id="msgViewStrength"></span>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-7">* Campo Obrigatório</p>
                <button type="submit" name="SendAddUserFinal" class="btn-success" value="Cadastrar">Cadastrar</button>
            </form>
        </div>
    </div>
</div>