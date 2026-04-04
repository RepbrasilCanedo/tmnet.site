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
//echo "<pre>"; var_dump($this->data);echo "</pre>"; die;
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Colaborador(a)</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users']) {
                    echo "<a href='" . URLADM . "list-users/index' class='btn-info'>Listar</a> ";
                }
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_users']) {
                        echo "<a href='" . URLADM . "view-users/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
                <?php
                $id = "";
                if (isset($valorForm['id'])) {
                    $id = $valorForm['id'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome completo" value="<?php echo $name; ?>" required>
                    </div>
                    
                    <div class="column">
                        <?php
                        $apelido = "";
                        if (isset($valorForm['apelido'])) {
                            $apelido = $valorForm['apelido'];
                        }
                        ?>
                        <label class="title-input">Apelido:<span class="text-danger">*</span></label>
                        <input type="text" name="apelido" id="apelido" class="input-adm" placeholder="Digite o apelido" value="<?php echo $apelido; ?>" required>
                    </div>

                    <?php if ($_SESSION['adms_access_level_id'] < 2) { ?>

                        <div class="column">
                            <?php
                            $empresa_id = "";
                            if (isset($valorForm['empresa_id'])) {
                                $empresa_id = $valorForm['empresa_id'];
                            }
                            ?>
                            <label class="title-input">Empresa: <span class="text-danger">*</span></label>
                            <select name="empresa_id" id="empresa_id" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['emp'] as $emp) {
                                    extract($emp);
                                    if ((isset($valorForm['adms_sits_user_id'])) and ($valorForm['adms_sits_user_id'] == $id_emp)) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    <?php } ?>

                    <div class="column">
                        <?php
                        $email = "";
                        if (isset($valorForm['email'])) {
                            $email = $valorForm['email'];
                        }
                        ?>
                        <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="input-adm" placeholder="Digite o seu melhor e-mail" value="<?php echo $email; ?>" required>

                    </div>

                    <div class="column">
                        <?php
                            $data_nascimento = "";
                            if (isset($valorForm['data_nascimento'])) {
                                $data_nascimento = $valorForm['data_nascimento'];
                            }
                        ?>
                        <label class="title-input">Data Nascimento:<span class="text-danger">*</span></label>
                        <input type="date" name="data_nascimento" id="data_nascimento" class="input-adm" placeholder="Digite sua data de Nascimento" value="<?php echo $data_nascimento; ?>" required>

                    </div>  
                

                    <div class="column">
                        <?php
                        $tel_1 = "";
                        if (isset($valorForm['tel_1'])) {
                            $tel_1 = $valorForm['tel_1'];
                        }
                        ?>
                        <label class="title-input">Telefone Principal: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_1" id="tel_1" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?php echo $tel_1; ?> " required>
                    </div>
                </div>
                

                <div class="row-input">
                    <div class="column">
                        
                        <label class="title-input">Estilo de Jogo</label>
                        <select name="estilo_jogo" class="input-adm">
                            <option value="Classista">Classista</option>
                            <option value="Caneteiro">Caneteiro</option>
                            <option value="Semiclassista">Semiclassista</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Mão Dominante</label>
                        <select name="mao_dominante" class="input-adm">
                            </option>
                            <option value="Destro">Destro</option>
                            <option value="Canhoto">Canhoto</option>
                        </select>
                    </div>
                    <div class="column">
                        <label class="title-input">Sexo/Paralímpico</label>
                        <select name="sexo" class="input-adm"> 
                            </option>
                            <option value="1">Masculino</option>
                            <option value="2">Feminino</option>
                            <option value="3">Masculino/Paralimpico</option>
                            <option value="4">Feminino/Paralimpico</option>
                        </select>
                    </div>
                </div>

                <div class="row-input">

                    <div class="column">
                        <?php
                        $tel_2 = "";
                        if (isset($valorForm['tel_2'])) {
                            $tel_2 = $valorForm['tel_2'];
                        }
                        ?>
                        <label class="title-input">Telefone Secundário: <span class="text-danger">*</span></label>
                        <input type="text" name="tel_2" id="tel_2" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?php echo $tel_2; ?>" required>
                    </div>

                    <div class="column">
                        <?php
                        $user = "";
                        if (isset($valorForm['user'])) {
                            $user = $valorForm['user'];
                        }
                        ?>
                        <label class="title-input">Usuário:<span class="text-danger">*</span></label>
                        <input type="text" name="user" id="user" class="input-adm" placeholder="Digite o usuário para acessar o administrativo" value="<?php echo $user; ?>" required>

                    </div>

                    <div class="column">
                        <?php
                        $adms_sits_user_id = "";
                        if (isset($valorForm['adms_sits_user_id'])) {
                            $adms_sits_user_id = $valorForm['adms_sits_user_id'];
                        }
                        ?>
                        <label class="title-input">Situação:<span class="text-danger">*</span></label>
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
                </div>

                <div class="row-input">

                    <div class="column">
                        <?php
                        $adms_access_level_id = "";
                        if (isset($valorForm['adms_access_level_id'])) {
                            $adms_access_level_id = $valorForm['adms_access_level_id'];
                        }
                        ?>
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
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditUser" class="btn-warning" value="Salvar">Salvar</button>
            </form>
        </div>
    </div>
</div>
<script>
        // mascara para telefone fixo e movel
    function mascaraTelefone(t) {
        let v = t.value;
        
        // Remove tudo o que não é dígito
        v = v.replace(/\D/g, "");
        
        // Aplica a máscara de DDD: (00)
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
        
        // Se tiver 11 dígitos, é celular: (00) 00000-0000
        if (v.length > 13) {
            v = v.replace(/(\d{5})(\d)/, "$1-$2");
        } 
        // Se tiver 10 dígitos, é fixo: (00) 0000-0000
        else {
            v = v.replace(/(\d{4})(\d)/, "$1-$2");
        }
        
        t.value = v;
    }
</script>
<!-- Fim do conteudo do administrativo -->