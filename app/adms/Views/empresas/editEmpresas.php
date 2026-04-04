<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

$valorForm = $this->data['form'][0] ?? $this->data['form'] ?? [];
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Clientes</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_empresas']) {
                    echo "<a href='" . URLADM . "list-empresas/index' class='btn-info'>Listar</a> ";
                }
                if (isset($valorForm['id']) && $this->data['button']['view_empresas']) {
                    echo "<a href='" . URLADM . "view-empresas/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a>";
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
            <form method="POST" action="" id="form-edit-empresas" class="form-adm">
                <input type="hidden" name="id" id="id" value="<?= $valorForm['id'] ?? '' ?>">

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Razão Social<span class="text-danger">*</span></label>
                        <input type="text" name="razao_social" id="razao_social" class="input-adm" value="<?= $valorForm['razao_social'] ?? '' ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Nome de Fantasia<span class="text-danger">*</span></label>
                        <input type="text" name="nome_fantasia" id="nome_fantasia" class="input-adm" value="<?= $valorForm['nome_fantasia'] ?? '' ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Cnpj/Cpf<span class="text-danger">*</span></label>
                        <input type="text" name="cnpjcpf" id="cnpjcpf" class="input-adm" maxlength="18" 
                               oninput="mascaraCpfCnpj(this)" onblur="validarDocumento(this.value)" 
                               value="<?= $valorForm['cnpjcpf'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cep<span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="text" name="cep" id="cep" class="input-adm" maxlength="9" 
                                   oninput="mascaraCep(this)" onblur="pesquisacep(this.value);" 
                                   value="<?= $valorForm['cep'] ?? '' ?>" required>
                             <small id="cep-status" style="display:none; color: #007bff; position: absolute; right: 10px; top: 10px;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </small>
                        </div>
                    </div>

                    <div class="column">
                        <label class="title-input">Logradouro<span class="text-danger">*</span></label>
                        <input type="text" name="logradouro" id="logradouro" class="input-adm" value="<?= $valorForm['logradouro'] ?? '' ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Bairro<span class="text-danger">*</span></label>
                        <input type="text" name="bairro" id="bairro" class="input-adm" value="<?= $valorForm['bairro'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cidade<span class="text-danger">*</span></label>
                        <input type="text" name="cidade" id="cidade" class="input-adm" value="<?= $valorForm['cidade'] ?? '' ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">UF<span class="text-danger">*</span></label>
                        <input type="text" name="uf" id="uf" class="input-adm" value="<?= $valorForm['uf'] ?? '' ?>" required>
                    </div>

                    <div class="column">
                        <label class="title-input">Situação da Empresa:<span class="text-danger">*</span></label>
                        <select name="situacao" id="situacao" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php foreach ($this->data['select']['sit_empresas'] as $sit): ?>
                                <option value="<?= $sit['id_sit'] ?>" <?= (isset($valorForm['situacao']) && $valorForm['situacao'] == $sit['id_sit']) ? 'selected' : '' ?>>
                                    <?= $sit['name_sit'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>
                <button type="submit" name="SendEditEmpresas" class="btn-warning" value="Salvar">Salvar Alterações</button>
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
    let valido = (doc.length === 11) ? validarCPF(doc) : (doc.length === 14 ? validarCNPJ(doc) : false);
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
    if (resto >= 10) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;
    soma = 0;
    for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i-1, i)) * (12 - i);
    resto = (soma * 10) % 11;
    if (resto >= 10) resto = 0;
    return resto === parseInt(cpf.substring(10, 11));
}

function validarCNPJ(cnpj) {
    if (/^(\d)\1{13}$/.test(cnpj)) return false;
    let b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    for (let i = 0, n = 0; i < 12; n += cnpj[i] * b[++i]);
    if (cnpj[12] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
    for (let i = 0, n = 0; i <= 12; n += cnpj[i] * b[i++]);
    if (cnpj[13] != (((n %= 11) < 2) ? 0 : 11 - n)) return false;
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
        }
    } catch (e) { console.error(e); }
    finally { if(statusInfo) statusInfo.style.display = 'none'; }
}
</script>