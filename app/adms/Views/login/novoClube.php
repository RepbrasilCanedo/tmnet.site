<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$valorForm = $this->data['form'] ?? [];
?>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
    body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .box-login { max-width: 650px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    .logo-container { text-align: center; margin-bottom: 20px; }
    .logo-container img { max-width: 180px; object-fit: contain; }
    .title-login { font-size: 24px; color: #0044cc; text-align: center; margin-bottom: 5px; font-weight: bold; }
    .subtitle-login { text-align: center; color: #666; font-size: 14px; margin-bottom: 30px; }
    
    .row-input { margin-bottom: 15px; }
    .row-flex { display: flex; gap: 15px; flex-wrap: wrap; }
    .row-flex .column { flex: 1; min-width: 200px; }
    
    .title-input { display: block; font-size: 13px; color: #333; font-weight: bold; margin-bottom: 5px; }
    .input-adm { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; transition: 0.3s; }
    .input-adm:focus { border-color: #0044cc; outline: none; box-shadow: 0 0 0 3px rgba(0,68,204,0.1); }
    
    .btn-success { width: 100%; background: #28a745; color: white; padding: 14px; border: none; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; margin-top: 10px; }
    .btn-success:hover { background: #218838; }
    
    .link-login { display: block; text-align: center; margin-top: 20px; color: #0044cc; text-decoration: none; font-size: 14px; font-weight: bold; }
    .link-login:hover { text-decoration: underline; }
    
    .alert-danger { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; border: 1px solid #f5c6cb; }
    .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; border: 1px solid #c3e6cb; }
    
    /* Estilo para campos bloqueados do ViaCEP */
    .input-readonly { background-color: #e9ecef; cursor: not-allowed; }
</style>

<div class="box-login">
    <div class="logo-container">
        <img src="<?= URLADM ?>app/adms/assets/image/empresa/1/logo.png" alt="TMNet Logo" onerror="this.style.display='none';">
    </div>
    
    <h2 class="title-login">Cadastre o seu Clube</h2>
    <p class="subtitle-login">Junte-se à maior plataforma de Tênis de Mesa e organize os seus torneios com facilidade.</p>

    <?php
    if (isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="">
        
        <div style="background: #eef2fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #cce5ff;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #0044cc; font-size: 15px;"><i class="fa-solid fa-shield"></i> Dados do Clube / Organização</h4>
            
            <div class="row-flex">
                <div class="column" style="flex: 2;">
                    <label class="title-input">Nome do Clube / Associação <span style="color:red;">*</span></label>
                    <input type="text" name="nome_clube" class="input-adm" placeholder="Associação de Tênis de Mesa..." value="<?= $valorForm['nome_clube'] ?? '' ?>" required>
                </div>
                <div class="column" style="flex: 1;">
                    <label class="title-input">CPF ou CNPJ <span style="color:red;">*</span></label>
                    <input type="text" name="cpf_cnpj" id="cpf_cnpj" class="input-adm" placeholder="Apenas números" oninput="mascaraCpfCnpj(this)" maxlength="18" value="<?= $valorForm['cpf_cnpj'] ?? '' ?>" required>
                </div>
            </div>

            <div class="row-flex" style="margin-top: 15px;">
                <div class="column" style="flex: 1;">
                    <label class="title-input">CEP <span style="color:red;">*</span></label>
                    <input type="text" name="cep" id="cep" class="input-adm" placeholder="00000-000" maxlength="9" onblur="pesquisarCep(this.value);" value="<?= $valorForm['cep'] ?? '' ?>" required>
                </div>
                <div class="column" style="flex: 2;">
                    <label class="title-input">Cidade</label>
                    <input type="text" name="cidade" id="cidade" class="input-adm input-readonly" placeholder="Preenchimento automático" value="<?= $valorForm['cidade'] ?? '' ?>" readonly>
                </div>
                <div class="column" style="flex: 0.5;">
                    <label class="title-input">UF</label>
                    <input type="text" name="estado" id="estado" class="input-adm input-readonly" placeholder="UF" value="<?= $valorForm['estado'] ?? '' ?>" readonly>
                </div>
            </div>

            <div class="row-flex" style="margin-top: 15px;">
                <div class="column" style="flex: 2;">
                    <label class="title-input">Logradouro / Rua</label>
                    <input type="text" name="logradouro" id="logradouro" class="input-adm input-readonly" placeholder="Preenchimento automático" value="<?= $valorForm['logradouro'] ?? '' ?>" readonly>
                </div>
                <div class="column" style="flex: 1.5;">
                    <label class="title-input">Bairro</label>
                    <input type="text" name="bairro" id="bairro" class="input-adm input-readonly" placeholder="Preenchimento automático" value="<?= $valorForm['bairro'] ?? '' ?>" readonly>
                </div>
            </div>
        </div>

        <div style="background: #fdfdfd; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #eee;">
            <h4 style="margin-top: 0; margin-bottom: 15px; color: #333; font-size: 15px;"><i class="fa-solid fa-user"></i> Conta do Administrador</h4>
            
            <div class="row-flex">
                <div class="column" style="flex: 2;">
                    <label class="title-input">Nome do Responsável <span style="color:red;">*</span></label>
                    <input type="text" name="nome_responsavel" class="input-adm" placeholder="Seu nome completo" value="<?= $valorForm['nome_responsavel'] ?? '' ?>" required>
                </div>
                <div class="column" style="flex: 1;">
                    <label class="title-input">Telefone / WhatsApp <span style="color:red;">*</span></label>
                    <input type="text" name="telefone" class="input-adm" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" maxlength="15" value="<?= $valorForm['telefone'] ?? '' ?>" required>
                </div>
            </div>
            
            <div class="row-input" style="margin-top: 15px;">
                <label class="title-input">E-mail de Contato <span style="color:red;">*</span></label>
                <input type="email" name="email" class="input-adm" placeholder="seu@email.com" value="<?= $valorForm['email'] ?? '' ?>" required>
            </div>
            
            <div class="row-flex" style="margin-top: 15px;">
                <div class="column">
                    <label class="title-input">Usuário (Login) <span style="color:red;">*</span></label>
                    <input type="text" name="user" class="input-adm" placeholder="Crie um login" value="<?= $valorForm['user'] ?? '' ?>" required autocapitalize="none">
                </div>
                <div class="column">
                    <label class="title-input">Senha <span style="color:red;">*</span></label>
                    <input type="password" name="password" class="input-adm" placeholder="Sua senha" required>
                </div>
            </div>
        </div>
        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <!-- Cptcha nuvem <div class="g-recaptcha" data-sitekey="6LckDq0sAAAAADjSgFKX_9FFnEGbIqJVW8ncKBAm"></div>-->                
            <div class="g-recaptcha" data-sitekey="6Ld9RrAsAAAAALwfG6hGwqyJexhNrLx2Rb4sq4_m"></div>
        </div>

        <button type="submit" name="SendNewClub" value="Cadastrar" class="btn-success">✅ Solicitar Acesso à Plataforma</button>
        
        <a href="<?= URLADM ?>login/index" class="link-login">Já tem conta? Faça Login aqui.</a>
    </form>
</div>

<script>
    // Máscara Dinâmica para CPF (11) ou CNPJ (14)
    function mascaraCpfCnpj(t) {
        let v = t.value.replace(/\D/g,"");
        if (v.length <= 11) { // CPF
            v = v.replace(/(\d{3})(\d)/,"$1.$2");
            v = v.replace(/(\d{3})(\d)/,"$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/,"$1-$2");
        } else { // CNPJ
            v = v.replace(/^(\d{2})(\d)/,"$1.$2");
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3");
            v = v.replace(/\.(\d{3})(\d)/,".$1/$2");
            v = v.replace(/(\d{4})(\d)/,"$1-$2");
        }
        t.value = v;
    }

    // Máscara de Telefone
    function mascaraTelefone(t) {
        let v = t.value.replace(/\D/g, ""); 
        v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); 
        if (v.length > 13) {
            v = v.replace(/(\d{5})(\d)/, "$1-$2"); 
        } else {
            v = v.replace(/(\d{4})(\d)/, "$1-$2"); 
        }
        t.value = v;
    }

    // Busca ViaCEP
    function limpa_formulário_cep() {
        document.getElementById('cidade').value = "";
        document.getElementById('estado').value = "";
    }

    // Busca ViaCEP
    function limpa_formulário_cep() {
        document.getElementById('logradouro').value = "";
        document.getElementById('bairro').value = "";
        document.getElementById('cidade').value = "";
        document.getElementById('estado').value = "";
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('logradouro').value = (conteudo.logradouro);
            document.getElementById('bairro').value = (conteudo.bairro);
            document.getElementById('cidade').value = (conteudo.localidade);
            document.getElementById('estado').value = (conteudo.uf);
        } else {
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisarCep(valor) {
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                document.getElementById('logradouro').value = "...";
                document.getElementById('bairro').value = "...";
                document.getElementById('cidade').value = "...";
                document.getElementById('estado').value = "...";
                var script = document.createElement('script');
                script.src = 'https://viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';
                document.body.appendChild(script);
            } else {
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } else {
            limpa_formulário_cep();
        }
    }
</script>