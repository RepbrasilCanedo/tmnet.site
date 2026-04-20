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
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Colaborador / Atleta</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users']) {
                    echo "<a href='" . URLADM . "list-users/index' class='btn-info'>Listar Todos</a> ";
                }
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_users']) {
                        echo "<a href='" . URLADM . "view-users/index/" . $valorForm['id'] . "' class='btn-primary' style='margin-left: 5px;'>Visualizar</a>";
                    }
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
            <form method="POST" action="" id="form-edit-user" class="form-adm">
                <input type="hidden" name="id" id="id" value="<?php echo $valorForm['id'] ?? ''; ?>">

                <div style="background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <h4 style="color: #0044cc; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 5px;">📄 Dados Pessoais & Contato</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Nome Completo:<span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome completo" value="<?php echo $valorForm['name'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="column">
                            <label class="title-input">Apelido na Mesa:<span class="text-danger">*</span></label>
                            <input type="text" name="apelido" id="apelido" class="input-adm" placeholder="Digite o apelido" value="<?php echo $valorForm['apelido'] ?? ''; ?>" required>
                        </div>

                        <div class="column">
                            <label class="title-input">Data Nascimento:<span class="text-danger">*</span></label>
                            <input type="date" name="data_nascimento" id="data_nascimento" class="input-adm" value="<?php echo $valorForm['data_nascimento'] ?? ''; ?>" required>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="input-adm" placeholder="Digite o melhor e-mail" value="<?php echo $valorForm['email'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="column">
                            <label class="title-input">WhatsApp / Telefone: <span class="text-danger">*</span></label>
                            <input type="text" name="telefone" id="telefone" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?php echo $valorForm['telefone'] ?? ''; ?>" required>
                        </div>
                        
                        <div class="column">
                            <label class="title-input">Sexo/Categoria Base:<span class="text-danger">*</span></label>
                            <select name="sexo" class="input-adm" required> 
                                <option value="">Selecione</option>
                                <option value="1" <?= (isset($valorForm['sexo']) && $valorForm['sexo'] == '1') ? 'selected' : '' ?>>Masculino</option>
                                <option value="2" <?= (isset($valorForm['sexo']) && $valorForm['sexo'] == '2') ? 'selected' : '' ?>>Feminino</option>
                                <option value="3" <?= (isset($valorForm['sexo']) && $valorForm['sexo'] == '3') ? 'selected' : '' ?>>Masculino/Paralímpico</option>
                                <option value="4" <?= (isset($valorForm['sexo']) && $valorForm['sexo'] == '4') ? 'selected' : '' ?>>Feminino/Paralímpico</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="background: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <h4 style="color: #0044cc; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 5px;">🏓 Perfil Técnico</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Estilo de Jogo</label>
                            <select name="estilo_jogo" class="input-adm">
                                <option value="">Selecione</option>
                                <option value="Classista" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Classista') ? 'selected' : '' ?>>Classista</option>
                                <option value="Caneteiro" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Caneteiro') ? 'selected' : '' ?>>Caneteiro</option>
                                <option value="Classineta" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Classineta') ? 'selected' : '' ?>>Classineta</option>
                                <option value="Pino Curto / Longo" <?= (isset($valorForm['estilo_jogo']) && $valorForm['estilo_jogo'] == 'Pino Curto / Longo') ? 'selected' : '' ?>>Pino Curto / Longo</option>
                            </select>
                        </div>
                        <div class="column">
                            <label class="title-input">Mão Dominante</label>
                            <select name="mao_dominante" class="input-adm">
                                <option value="">Selecione</option>
                                <option value="Destro" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Destro') ? 'selected' : '' ?>>Destro</option>
                                <option value="Canhoto" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Canhoto') ? 'selected' : '' ?>>Canhoto</option>
                                <option value="Ambidestro" <?= (isset($valorForm['mao_dominante']) && $valorForm['mao_dominante'] == 'Ambidestro') ? 'selected' : '' ?>>Ambidestro</option>
                            </select>
                        </div>
                        <div class="column">
                            <label class="title-input">Pontuação Ranking:</label>
                            <input type="number" name="pontuacao_ranking" id="pontuacao_ranking" class="input-adm" placeholder="0" value="<?php echo $valorForm['pontuacao_ranking'] ?? '0'; ?>">
                        </div>
                    </div>
                </div>

                <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    <h4 style="color: #856404; margin-top: 0; border-bottom: 1px solid #ffeeba; padding-bottom: 5px;">🔐 Controle de Acesso</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Usuário (Login):<span class="text-danger">*</span></label>
                            <input type="text" name="user" id="user" class="input-adm" placeholder="Login para acessar o sistema" value="<?php echo $valorForm['user'] ?? ''; ?>" required>
                        </div>

                        <div class="column">
                            <label class="title-input">Situação do Cadastro:<span class="text-danger">*</span></label>
                            <select name="adms_sits_user_id" id="adms_sits_user_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['sit_user'] as $sit) {
                                    extract($sit);
                                    if ((isset($valorForm['adms_sits_user_id'])) and ($valorForm['adms_sits_user_id'] == $id_sit)) {
                                        echo "<option value='$id_sit' selected>$name_sit</option>";
                                    } else {
                                        echo "<option value='$id_sit'>$name_sit</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <label class="title-input">Nível de Acesso:<span class="text-danger">*</span></label>
                            <select name="adms_access_level_id" id="adms_access_level_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['lev'] as $lev) {
                                    extract($lev);
                                    if ((isset($valorForm['adms_access_level_id'])) and ($valorForm['adms_access_level_id'] == $id_lev)) {
                                        echo "<option value='$id_lev' selected>$name_lev</option>";
                                    } else {
                                        echo "<option value='$id_lev'>$name_lev</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <?php if ($_SESSION['adms_access_level_id'] < 2) { ?>
                            <div class="column">
                                <label class="title-input">Empresa / Clube: <span class="text-danger">*</span></label>
                                <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                    <option value="">Selecione</option>
                                    <?php
                                    foreach ($this->data['select']['emp'] as $emp) {
                                        extract($emp);
                                        if ((isset($valorForm['empresa_id'])) and ($valorForm['empresa_id'] == $id_emp)) {
                                            echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                        } else {
                                            echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <button type="submit" name="SendEditUser" class="btn-success" value="Salvar" style="width: 100%; padding: 12px; font-size: 16px; font-weight: bold; background-color: #28a745; border: none; border-radius: 4px; color: white; cursor: pointer;">
                    💾 Salvar Alterações
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // mascara para telefone fixo e movel
    function mascaraTelefone(t) {
        let v = t.value;
        v = v.replace(/\D/g, "");
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        
        if (v.length > 13) {
            v = v.replace(/(\d{5})(\d)/, "$1-$2");
        } else {
            v = v.replace(/(\d{4})(\d)/, "$1-$2");
        }
        t.value = v;
    }
</script>