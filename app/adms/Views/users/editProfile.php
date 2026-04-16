<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }
$valorForm = $this->data['form'][0] ?? $this->data['form'] ?? [];
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Meu Passaporte TMNet</span>
            <div class="top-list-right">
                <?php if (isset($this->data['button']['view_profile'])): ?>
                    <a href="<?= URLADM ?>view-profile/index" class="btn-primary" style="background-color: #6c757d; border: none;">⬅️ Voltar ao Perfil</a>
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
        </div>

        <div class="content-adm">
            <form method="POST" action="" id="form-edit-profile" class="form-adm">

                <div style="background: #fdfdfd; border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #0044cc; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px;">📄 Identificação Pessoal</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Nome Completo:<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="input-adm" placeholder="Digite o nome completo" value="<?= $valorForm['name'] ?? '' ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">Apelido na Mesa (Opcional):</label>
                            <input type="text" name="apelido" class="input-adm" placeholder="Ex: Zé, Caneta..." value="<?= $valorForm['apelido'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Data de Nascimento:<span class="text-danger">*</span></label>
                            <input type="date" name="data_nascimento" class="input-adm" value="<?= $valorForm['data_nascimento'] ?? '' ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">Documento Oficial (RG/CPF):<span class="text-danger">*</span></label>
                            <input type="text" name="rg" class="input-adm" placeholder="Número do Documento" value="<?= $valorForm['rg'] ?? '' ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">Grau de Escolaridade:<span class="text-danger">*</span></label>
                            <select name="escolaridade" class="input-adm" required>
                                <option value="">Selecione...</option>
                                <?php
                                $esc = $valorForm['escolaridade'] ?? '';
                                $opcoesEsc = ['Ensino Fundamental', 'Ensino Médio', 'Ensino Superior Incompleto', 'Ensino Superior Completo', 'Pós-graduação / Mestrado / Doutorado'];
                                foreach ($opcoesEsc as $opcao) {
                                    $sel = ($esc === $opcao) ? 'selected' : '';
                                    echo "<option value='$opcao' $sel>$opcao</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="background: #fdfdfd; border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #0044cc; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px;">📍 Localização</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">CEP:<span class="text-danger">*</span></label>
                            <input type="text" name="cep" id="cep" class="input-adm" placeholder="00000-000" maxlength="9" onblur="pesquisarCep(this.value);" value="<?= $valorForm['cep'] ?? '' ?>" required>
                        </div>
                        <div class="column" style="flex: 2;">
                            <label class="title-input">Logradouro / Endereço:<span class="text-danger">*</span></label>
                            <input type="text" name="endereco" id="endereco" class="input-adm" placeholder="Rua, Avenida..." value="<?= $valorForm['endereco'] ?? '' ?>" required>
                        </div>
                        <div class="column" style="flex: 0.5;">
                            <label class="title-input">Número:<span class="text-danger">*</span></label>
                            <input type="text" name="numero" class="input-adm" placeholder="Nº" value="<?= $valorForm['numero'] ?? '' ?>" required>
                        </div>
                    </div>

                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Bairro:<span class="text-danger">*</span></label>
                            <input type="text" name="bairro" id="bairro" class="input-adm" placeholder="Seu bairro" value="<?= $valorForm['bairro'] ?? '' ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">Cidade:<span class="text-danger">*</span></label>
                            <input type="text" name="cidade" id="cidade" class="input-adm" placeholder="Sua cidade" value="<?= $valorForm['cidade'] ?? '' ?>" required>
                        </div>
                        <div class="column" style="flex: 0.5;">
                            <label class="title-input">Estado:<span class="text-danger">*</span></label>
                            <input type="text" name="estado" id="estado" class="input-adm" placeholder="UF" maxlength="2" value="<?= $valorForm['estado'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div style="background: #fdfdfd; border: 1px solid #eee; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #0044cc; margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px;">📱 Contato e Redes</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">E-mail:<span class="text-danger">*</span></label>
                            <input type="email" name="email" class="input-adm" placeholder="E-mail de acesso" value="<?= $valorForm['email'] ?? '' ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">WhatsApp / Telefone:<span class="text-danger">*</span></label>
                            <input type="text" name="telefone" class="input-adm" maxlength="15" placeholder="(00) 00000-0000" oninput="mascaraTelefone(this)" value="<?= trim($valorForm['telefone'] ?? '') ?>" required>
                        </div>
                        <div class="column">
                            <label class="title-input">Instagram (Opcional):</label>
                            <input type="text" name="instagram" class="input-adm" placeholder="@seu_perfil" value="<?= $valorForm['instagram'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <div style="background: #fff3cd; border: 1px solid #ffeeba; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <h4 style="color: #856404; margin-top: 0; border-bottom: 1px solid #ffeeba; padding-bottom: 8px; margin-bottom: 15px;">🔐 Autenticação</h4>
                    
                    <div class="row-input">
                        <div class="column">
                            <label class="title-input">Nome de Usuário (Login):<span class="text-danger">*</span></label>
                            <input type="text" name="user" class="input-adm" placeholder="Login do sistema" value="<?= $valorForm['user'] ?? '' ?>" required autocapitalize="none">
                        </div>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4" style="margin-bottom: 15px;">* Campos Obrigatórios</p>

                <button type="submit" name="SendEditProfile" class="btn-success" value="Salvar" style="width: 100%; padding: 14px; font-size: 16px; font-weight: bold; background-color: #28a745; border: none; border-radius: 6px; color: white; cursor: pointer; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    💾 Guardar Meu Passaporte
                </button>

            </form>
        </div>
    </div>
</div>

<script>
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

    function limpa_formulário_cep() {
        document.getElementById('endereco').value=("");
        document.getElementById('bairro').value=("");
        document.getElementById('cidade').value=("");
        document.getElementById('estado').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            document.getElementById('endereco').value=(conteudo.logradouro);
            document.getElementById('bairro').value=(conteudo.bairro);
            document.getElementById('cidade').value=(conteudo.localidade);
            document.getElementById('estado').value=(conteudo.uf);
        } else {
            limpa_formulário_cep();
            alert("CEP não encontrado. Por favor, digite o endereço manualmente.");
        }
    }

    function pesquisarCep(valor) {
        var cep = valor.replace(/\D/g, '');
        if (cep != "") {
            var validacep = /^[0-9]{8}$/;
            if(validacep.test(cep)) {
                document.getElementById('endereco').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('cidade').value="...";
                document.getElementById('estado').value="...";
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