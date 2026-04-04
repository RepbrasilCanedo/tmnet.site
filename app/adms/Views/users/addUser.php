<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Colaborador(a) / Atleta</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users']) {
                    echo "<a href='" . URLADM . "list-users/index' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-add-user" class="form-adm">
                
                <div class="row-input">
                    <div class="column">
                        <?php $name = $valorForm['name'] ?? ""; ?>
                        <label class="title-input">Nome Completo:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome completo" value="<?= $name; ?>" required>
                    </div>
                    
                    <div class="column">
                        <?php $apelido = $valorForm['apelido'] ?? ""; ?>
                        <label class="title-input">Apelido / Nome de Urna:<span class="text-danger">*</span></label>
                        <input type="text" name="apelido" id="apelido" class="input-adm" placeholder="Nome que aparece nas mesas" value="<?= $apelido; ?>" required>
                    </div>

                    <?php if ($_SESSION['adms_access_level_id'] <= 2) { ?>                        
                        <div class="column">
                            <?php $empresa_id = $valorForm['empresa_id'] ?? ""; ?>
                            <label class="title-input">Empresa / Clube: <span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp'] as $emp) {
                                    extract($emp);
                                    $selected = ((isset($valorForm['empresa_id'])) and ($valorForm['empresa_id'] == $id_emp)) ? "selected" : "";
                                    echo "<option value='$id_emp' $selected>$nome_fantasia_emp</option>";
                                }
                                ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Data de Nascimento:<span class="text-danger">*</span></label>
                        <?php $data_nascimento = $valorForm['data_nascimento'] ?? ""; ?>
                        <input type="date" name="data_nascimento" id="data_nascimento" class="input-adm" value="<?= $data_nascimento; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Sexo / Categoria</label>
                        <?php $sexo = $valorForm['sexo'] ?? "1"; ?>
                        <select name="sexo" class="input-adm">
                            <option value="1" <?= ($sexo == "1") ? "selected" : "" ?>>Masculino</option>
                            <option value="2" <?= ($sexo == "2") ? "selected" : "" ?>>Feminino</option>
                            <option value="3" <?= ($sexo == "3") ? "selected" : "" ?>>Masculino Paralímpico</option>
                            <option value="4" <?= ($sexo == "4") ? "selected" : "" ?>>Feminino Paralímpico</option>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Estilo de Jogo</label>
                        <?php $estilo_jogo = $valorForm['estilo_jogo'] ?? "Classista"; ?>
                        <select name="estilo_jogo" class="input-adm">
                            <option value="Classista" <?= ($estilo_jogo == "Classista") ? "selected" : "" ?>>Classista</option>
                            <option value="Caneteiro" <?= ($estilo_jogo == "Caneteiro") ? "selected" : "" ?>>Caneteiro</option>
                            <option value="Semiclassista" <?= ($estilo_jogo == "Semiclassista") ? "selected" : "" ?>>Semiclassista</option>
                        </select>
                    </div>
                    
                    <div class="column">
                        <label class="title-input">Mão Dominante</label>
                        <?php $mao_dominante = $valorForm['mao_dominante'] ?? "Destro"; ?>
                        <select name="mao_dominante" class="input-adm">
                            <option value="Destro" <?= ($mao_dominante == "Destro") ? "selected" : "" ?>>Destro</option>
                            <option value="Canhoto" <?= ($mao_dominante == "Canhoto") ? "selected" : "" ?>>Canhoto</option>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php $email = $valorForm['email'] ?? ""; ?>
                        <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="input-adm" placeholder="Digite o seu melhor e-mail" value="<?= $email; ?>" required>
                    </div>

                    <div class="column">
                        <?php $tel_1 = $valorForm['tel_1'] ?? ""; ?>
                        <label class="title-input">Telefone Principal: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_1" id="tel_1" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?= trim($tel_1); ?>" required>
                    </div>

                    <div class="column">
                        <?php $tel_2 = $valorForm['tel_2'] ?? ""; ?>
                        <label class="title-input">Telefone Secundário: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_2" id="tel_2" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?= trim($tel_2); ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php $user = $valorForm['user'] ?? ""; ?>
                        <label class="title-input">Usuário de Login:<span class="text-danger">*</span></label>
                        <input type="text" name="user" id="user" class="input-adm" placeholder="Digite o usuário de acesso" value="<?= $user; ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Senha:<span class="text-danger">*</span></label>
                        <input type="password" name="password" id="password" class="input-adm" placeholder="Digite a senha" onkeyup="passwordStrength()" autocomplete="on" required>
                        <span id="msgViewStrength"></span>
                    </div>

                    <div class="column">
                        <label class="title-input">Nivel Acesso:<span class="text-danger">*</span></label>
                        <select name="adms_access_level_id" id="adms_access_level_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['lev'] as $lev) {
                                extract($lev);
                                $selected = ((isset($valorForm['adms_access_level_id'])) and ($valorForm['adms_access_level_id'] == $id_lev)) ? "selected" : "";
                                echo "<option value='$id_lev' $selected>$name_lev</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Situação do Perfil:<span class="text-danger">*</span></label>
                        <select name="adms_sits_user_id" id="adms_sits_user_id" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['sit'] as $sit) {
                                extract($sit);
                                $selected = ((isset($valorForm['adms_sits_user_id'])) and ($valorForm['adms_sits_user_id'] == $id_sit)) ? "selected" : "";
                                echo "<option value='$id_sit' $selected>$name_sit</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <?php $pontuacao_ranking = $valorForm['pontuacao_ranking'] ?? "0"; ?>
                        <label class="title-input">Pontuação Ranking Oficial:<span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="pontuacao_ranking" id="pontuacao_ranking" class="input-adm" placeholder="0" value="<?= $pontuacao_ranking; ?>" required>
                    </div>
                </div>
                
                <div style="margin-top: 20px;">
                    <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>
                    <button type="submit" name="SendAddUser" class="btn-success" value="Cadastrar">Cadastrar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // mascara para telefone fixo e movel
    function mascaraTelefone(t) {
        let v = t.value;
        v = v.replace(/\D/g, ""); // Remove tudo o que não é dígito
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); // Aplica a máscara de DDD
        if (v.length > 13) {
            v = v.replace(/(\d{5})(\d)/, "$1-$2"); // Celular
        } else {
            v = v.replace(/(\d{4})(\d)/, "$1-$2"); // Fixo
        }
        t.value = v;
    }
</script>