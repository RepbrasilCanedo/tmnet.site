<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Empresa</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_emp_principal']) {
                    echo "<a href='" . URLADM . "list-emp-principal/index' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-add-empresas" class="form-adm">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $razao_social = "";
                        if (isset($valorForm['razao_social'])) {
                            $razao_social = $valorForm['razao_social'];
                        }
                        ?>
                        <label class="title-input">Razão Social:<span class="text-danger">*</span></label>
                        <input type="text" name="razao_social" id="razao_social" class="input-adm" placeholder="Razão social" value="<?php echo $razao_social; ?>" required>
                    </div>
                    <div class="column">
                        <?php
                        $nome_fantasia = "";
                        if (isset($valorForm['nome_fantasia'])) {
                            $nome_fantasia = $valorForm['nome_fantasia'];
                        }
                        ?>
                        <label class="title-input">Nome Fantasia:<span class="text-danger">*</span></label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="input-adm" placeholder="Nome fantasia" value="<?php echo $nome_fantasia; ?>" required>

                    </div> 

                    <div class="column">
                            <?php
                            $cnpj = "";
                            if (isset($valorForm['cnpj'])) {
                                $cnpj = $valorForm['cnpj'];
                            }
                            ?>
                            <label class="title-input">Cnpj:<span class="text-danger">*</span></label>
                            <input type="text" name="cnpj" id="cnpj" class="input-adm" placeholder="Número do Cnpj" maxlength="18" oninput="mascaraCpfCnpj(this)" onblur="validarDocumento(this.value)" value="<?php echo $cnpj; ?>" required>
                    </div>                   
                </div>

                    <div class="row-input">                        
                        <div class="column">
                            <?php
                            $cep = "";
                            if (isset($valorForm['cep'])) {
                                $cep = $valorForm['cep'];
                            }
                            ?>
                            <label class="title-input">Cep:<span class="text-danger">*</span></label>
                            <input type="text" name="cep" id="cep" class="input-adm" placeholder="00000-000" value="<?php echo $cep; ?>" maxlength="9" oninput="mascaraCep(this)" onblur="pesquisacep(this.value);" value="<?php echo $cep; ?>" required>
                        </div>
                        <div class="column">
                            <?php
                            $logradouro = "";
                            if (isset($valorForm['logradouro'])) {
                                $logradouro = $valorForm['logradouro'];
                            }
                            ?>
                            <label class="title-input">Logradouro:<span class="text-danger">*</span></label>
                            <input type="text" name="logradouro" id="logradouro" class="input-adm" placeholder="Rua e numero" value="<?php echo $logradouro; ?>" required>
                        </div>
                        <div class="column">
                            <?php
                            $bairro = "";
                            if (isset($valorForm['bairro'])) {
                                $bairro = $valorForm['bairro'];
                            }
                            ?>
                            <label class="title-input">Bairro:<span class="text-danger">*</span></label>
                            <input type="text" name="bairro" id="bairro" class="input-adm" placeholder="Bairro" value="<?php echo $bairro; ?>" required>
                        </div>
                    </div>
                    <div class="row-input">
                        <div class="column">
                            <?php
                            $cidade = "";
                            if (isset($valorForm['cidade'])) {
                                $cidade = $valorForm['cidade'];
                            }
                            ?>
                            <label class="title-input">Cidade:<span class="text-danger">*</span></label>
                            <input type="text" name="cidade" id="cidade" class="input-adm" placeholder="Observação" value="<?php echo $cidade; ?>" required>
                        </div>
                        <div class="column">
                            <?php
                            $uf = "";
                            if (isset($valorForm['uf'])) {
                                $uf = $valorForm['uf'];
                            }
                            ?>
                            <label class="title-input">UF:<span class="text-danger">*</span></label>
                            <input type="text" name="uf" id="uf" class="input-adm" placeholder="Estado" value="<?php echo $uf; ?>" required>
                        </div>

                        <div class="column">
                            <label class="title-input">Situação da Empresa:<span class="text-danger">*</span></label>
                            <select name="situacao" id="situacao" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php
                                foreach ($this->data['select']['situacao'] as $sitempresas) {
                                    extract($sitempresas);
                                    if (isset($valorForm['situacao']) and $valorForm['situacao'] == $id) {
                                        echo "<option value='$id' selected>$name</option>";
                                    } else {
                                        echo "<option value='$id'>$name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <?php
                            $contato = "";
                            if (isset($valorForm['contato'])) {
                                $contato = $valorForm['contato'];
                            }
                            ?>
                            <label class="title-input">Contato<span class="text-danger">*</span></label>
                            <input type="text" name="contato" id="contato" class="input-adm" placeholder="Contato da empresa..." value="<?php echo $contato; ?>" required>
                        </div>
                        <div class="column">
                            <?php
                            $telefone = "";
                            if (isset($valorForm['telefone'])) {
                                $telefone = $valorForm['telefone'];
                            }
                            ?>
                            <label class="title-input">Telefone<span class="text-danger">*</span></label>
                            <input type="text" name="telefone" id="telefone" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)"  value="<?php echo $telefone; ?>" required>
                        </div>                       
                        
                        <div class="column">
                            <?php
                            $email = "";
                            if (isset($valorForm['email'])) {
                                $email = $valorForm['email'];
                            }
                            ?>
                            <label class="title-input">E-Mail:<span class="text-danger">*</span></label>
                            <input type="text" name="email" id="email" class="input-adm" placeholder="Estado" value="<?php echo $email; ?>" required>
                        </div>
                    </div>

                    <p class="text-danger mb-5 fs-6">* Campo Obrigatório</p>

                    <button type="submit" name="SendAddEmpPrincipal" class="btn-success" value="Cadastrar">Cadastrar</button>

            </form>
        </div>
    </div>
</div>

<script>

        // mascara para cnpj e cpf
    function mascaraCpfCnpj(t) {
        let v = t.value.replace(/\D/g, ""); // Remove tudo que não é número

        if (v.length <= 11) { // CPF: 000.000.000-00
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        } else { // CNPJ: 00.000.000/0000-00
            v = v.replace(/^(\d{2})(\d)/, "$1.$2");
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3");
            v = v.replace(/\.(\d{3})(\d)/, ".$1/$2");
            v = v.replace(/(\d{4})(\d)/, "$1-$2");
           
        }
        t.value = v;
    }

    // inicio do validador de cnpj e cpf

    
    function validarDocumento(valor) {
        const doc = valor.replace(/\D/g, ''); // Remove pontos, traços e barras

        if (doc.length === 11) {
            if (!validarCPF(doc)) {
                alert("CPF Inválido!");
                document.getElementById('cpf_cnpj').value = "";
            }
        } else if (doc.length === 14) {
            if (!validarCNPJ(doc)) {
                alert("CNPJ Inválido!");
                document.getElementById('cpf_cnpj').value = "";
            }
        } else if (doc.length > 0) {
            alert("Documento incompleto!");
            document.getElementById('cpf_cnpj').value = "";
        }
    }

    // --- Algoritmo de Validação de CPF ---
    function validarCPF(cpf) {
        if (/^(\d)\1{10}$/.test(cpf)) return false;
        let soma = 0, resto;
        for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) return false;
        soma = 0;
        for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) resto = 0;
        if (resto !== parseInt(cpf.substring(10, 11))) return false;
        return true;
    }

    // --- Algoritmo de Validação de CNPJ ---
    function validarCNPJ(cnpj) {
        if (/^(\d)\1{13}$/.test(cnpj)) return false;
        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0, pos = tamanho - 7;
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado != digitos.charAt(0)) return false;
        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;
        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }
        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado != digitos.charAt(1)) return false;
        return true;
    }

    // Função para criar a máscara 00000-000
    function mascaraCep(t) {
        var v = t.value;
        // Remove tudo o que não é dígito
        v = v.replace(/\D/g, "");
        // Coloca o hífen após o 5º dígito
        v = v.replace(/^(\d{5})(\d)/, "$1-$2");
        t.value = v;
    } 
    function pesquisacep(valor) {
    // Nova variável "cep" somente com dígitos.
    var cep = valor.replace(/\D/g, '');

    // Verifica se campo cep possui valor informado.
    if (cep != "") {
        // Expressão regular para validar o CEP.
        var validacep = /^[0-9]{8}$/;

        // Valida o formato do CEP.
        if(validacep.test(cep)) {

            // Preenche os campos com "..." enquanto consulta webservice.
            document.getElementById('logradouro').value="...";
            document.getElementById('bairro').value="...";
            document.getElementById('cidade').value="...";
            document.getElementById('uf').value="...";

            // Consulta o webservice viacep.com.br/
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(dados => {
                    if (!("erro" in dados)) {
                        // Atualiza os campos com os valores da consulta.
                        document.getElementById('logradouro').value = dados.logradouro;
                        document.getElementById('bairro').value = dados.bairro;
                        document.getElementById('cidade').value = dados.localidade;
                        document.getElementById('uf').value = dados.uf;
                    } else {
                        // CEP pesquisado não foi encontrado.
                        alert("CEP não encontrado.");
                        limpa_formulário_cep();
                    }
                })
                .catch(() => alert("Erro ao buscar o CEP."));
        } else {
            alert("Formato de CEP inválido.");
        }
    }
    }

    function limpa_formulário_cep() {
        document.getElementById('logradouro').value=("");
        document.getElementById('bairro').value=("");
        document.getElementById('cidade').value=("");
        document.getElementById('uf').value=("");
    }


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