<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

$valorForm = $this->data['form'] ?? [];

function old($field, $default = "") {
    global $valorForm;
    return $valorForm[$field] ?? $default;
}

function canEdit(array $allowedLevels) {
    $userLevel = (int) ($_SESSION['adms_access_level_id'] ?? 0);
    return in_array($userLevel, $allowedLevels) ? "" : "readonly style='background-color: #f8f9fa; cursor: not-allowed;'";
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Clientes</span>
            <div class="top-list-right">
                <?php if (isset($this->data['button']['list_empresas'])): ?>
                    <a href="<?= URLADM ?>list-empresas/index" class="btn-info">Listar</a>
                <?php endif; ?>
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
                        <label class="title-input">Razão Social:</label>
                        <input type="text" name="razao_social" class="input-adm" value="<?= old('razao_social') ?>" <?= canEdit([1, 2, 4, 12]) ?> required>
                    </div>

                    <div class="column">
                        <label class="title-input">Nome Fantasia:<span class="text-danger">*</span></label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="input-adm" value="<?= old('nome_fantasia') ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Cnpj/Cpf:<span class="text-danger">*</span></label>
                        <input type="text" name="cnpjcpf" id="cnpjcpf" class="input-adm" maxlength="18" 
                               oninput="mascaraCpfCnpj(this)" onblur="validarDocumento(this.value)"
                               value="<?= old('cnpjcpf') ?>" <?= canEdit([1, 2, 4, 12]) ?> required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cep:<span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="cep" id="cep" class="input-adm" placeholder="00000-000" value="<?= old('cep') ?>" maxlength="9" oninput="mascaraCep(this)" onblur="pesquisacep(this.value);" required>
                            <small id="cep-status" style="display:none; color: #007bff; position: absolute; right: 10px; top: 10px;">
                                <i class="fas fa-spinner fa-spin"></i> Buscando...
                            </small>
                        </div>
                    </div>
                    
                    <div class="column">
                        <label class="title-input">Logradouro:<span class="text-danger">*</span></label>
                        <input type="text" name="logradouro" id="logradouro" class="input-adm" value="<?= old('logradouro') ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Bairro:<span class="text-danger">*</span></label>
                        <input type="text" name="bairro" id="bairro" class="input-adm" value="<?= old('bairro') ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cidade:<span class="text-danger">*</span></label>
                        <input type="text" name="cidade" id="cidade" class="input-adm" value="<?= old('cidade') ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">UF:<span class="text-danger">*</span></label>
                        <input type="text" name="uf" id="uf" class="input-adm" value="<?= old('uf') ?>" required>
                    </div>

                    <?php if (in_array((int)($_SESSION['adms_access_level_id'] ?? 0), [1, 2])): ?>
                        <div class="column">
                            <label class="title-input">Cliente da Empresa:<span class="text-danger">*</span></label>
                            <select name="empresa" id="empresa" class="input-adm" required>
                                <option value="">Selecione</option>
                                <?php if(isset($this->data['select']['empresa'])): ?>
                                    <?php foreach ($this->data['select']['empresa'] as $clieEmpresas): ?>
                                        <option value="<?= $clieEmpresas['id'] ?>" <?= (old('empresa') == $clieEmpresas['id']) ? 'selected' : '' ?>>
                                            <?= $clieEmpresas['razao_social'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>
                <button type="submit" name="SendAddEmpresas" class="btn-success" value="Cadastrar">Cadastrar</button>
            </form>            
        </div>
    </div>
</div>

<script>
function mascaraCpfCnpj(t) {
    let v = t.value.replace(/\D/g, "");
    if (v.length <= 11) {
        v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    } else {
        v = v.replace(/^(\d{2})(\d)/, "$1.$2").replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3").replace(/\.(\d{3})(\d)/, ".$1/$2").replace(/(\d{4})(\d)/, "$1-$2");
    }
    t.value = v;
}

function validarDocumento(valor) {
    const doc = valor.replace(/\D/g, '');
    if (doc === "") return;
    let valido = false;
    if (doc.length === 11) {
        valido = validarCPF(doc);
    } else if (doc.length === 14) {
        valido = validarCNPJ(doc);
    }
    
    if (!valido) {
        alert("Documento (CPF/CNPJ) inválido!");
        document.getElementById('cnpjcpf').value = "";
        document.getElementById('cnpjcpf').focus();
    }
}

function validarCPF(cpf) {
    if (/^(\d)\1{10}$/.test(cpf)) return false;
    let soma = 0, resto;
    for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i-1, i)) * (11 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    return resto === parseInt(cpf.substring(10, 11));
}

function validarCNPJ(cnpj) {
    if (/^(\d)\1{13}$/.test(cnpj)) return false;
    let tamanho = cnpj.length - 2
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) return false;
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) return false;
    return true;
}

function mascaraCep(t) {
    t.value = t.value.replace(/\D/g, "").replace(/^(\d{5})(\d)/, "$1-$2");
}

async function pesquisacep(valor) {
    const cep = valor.replace(/\D/g, '');
    if (cep.length !== 8) return;
    const statusInfo = document.getElementById('cep-status');
    if(statusInfo) statusInfo.style.display = 'block';
    try {
        const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const dados = await response.json();
        if (!dados.erro) {
            document.getElementById('logradouro').value = dados.logradouro;
            document.getElementById('bairro').value = dados.bairro;
            document.getElementById('cidade').value = dados.localidade;
            document.getElementById('uf').value = dados.uf;
            document.getElementById('logradouro').focus();
        } else {
            alert("CEP não encontrado.");
        }
    } catch (e) {
        alert("Erro ao buscar CEP.");
    } finally {
        if(statusInfo) statusInfo.style.display = 'none';
    }
}
</script>