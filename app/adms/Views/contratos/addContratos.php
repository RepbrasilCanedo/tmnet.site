<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Contrato</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['list_contratos'])): ?>
                    <a href="<?= URLADM ?>list-contratos/index" class="btn-info">Listar</a>
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
            <form method="POST" action="" id="form-add-contrato" class="form-adm">
                
                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome/Descrição:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" 
                               placeholder="Ex: Contrato de Prestação de Serviços..." 
                               value="<?= $this->data['form']['name'] ?? '' ?>" required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Tipo de Contrato:<span class="text-danger">*</span></label>
                        <select name="tipo_contr" id="tipo_contr" class="input-adm" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($this->data['select']['tipo_contr'] as $tipo): ?>
                                <?php $selected = (isset($this->data['form']['tipo_contr']) && $this->data['form']['tipo_contr'] == $tipo['id']) ? 'selected' : ''; ?>
                                <option value="<?= $tipo['id'] ?>" <?= $selected ?>><?= $tipo['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="column">
                        <label class="title-input">Status:<span class="text-danger">*</span></label>
                        <select name="status" id="status" class="input-adm" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($this->data['select']['status'] as $sit): ?>
                                <?php $selected = (isset($this->data['form']['status']) && $this->data['form']['status'] == $sit['id']) ? 'selected' : ''; ?>
                                <option value="<?= $sit['id'] ?>" <?= $selected ?>><?= $sit['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Início do Contrato:<span class="text-danger">*</span></label>
                        <input type="date" name="inicio_contr" id="inicio_contr" class="input-adm" 
                               value="<?= $this->data['form']['inicio_contr'] ?? '' ?>" required>
                    </div>
                    
                    <div class="column">
                        <label class="title-input">Término do Contrato:</label>
                        <input type="date" name="final_contr" id="final_contr" class="input-adm" 
                               value="<?= $this->data['form']['final_contr'] ?? '' ?>">
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Cliente:<span class="text-danger">*</span></label>
                        <select name="cliente_id" id="cliente_id" class="input-adm" required>
                            <option value="">Selecione o Cliente...</option>
                            <?php 
                            if (!empty($this->data['select']['clientes'])) {
                                foreach ($this->data['select']['clientes'] as $cliente): 
                                    $selected = (isset($this->data['form']['cliente_id']) && $this->data['form']['cliente_id'] == $cliente['id']) ? 'selected' : ''; 
                            ?>
                                    <option value="<?= $cliente['id'] ?>" <?= $selected ?>><?= $cliente['razao_social'] ?></option>
                            <?php 
                                endforeach; 
                            }
                            ?>
                        </select>
                    </div>

                    <div class="column">
                        <label class="title-input">Categoria/Tipo:<span class="text-danger">*</span></label>
                        <select name="tipo" id="tipo" class="input-adm" required onchange="toggleQuantidade()">
                            <option value="">Selecione...</option>
                            <option value="1" <?= (isset($this->data['form']['tipo']) && $this->data['form']['tipo'] == 1) ? 'selected' : '' ?>>Hardware</option>
                            <option value="2" <?= (isset($this->data['form']['tipo']) && $this->data['form']['tipo'] == 2) ? 'selected' : '' ?>>Software</option>
                            <option value="3" <?= (isset($this->data['form']['tipo']) && $this->data['form']['tipo'] == 3) ? 'selected' : '' ?>>Serviço</option>
                        </select>
                    </div>

                    <div class="column" id="div_quantidade" style="display: none;">
                        <label class="title-input">Quantidade:</label>
                        <input type="number" name="quant" id="quant" class="input-adm" 
                               placeholder="Ex: 1" 
                               value="<?= $this->data['form']['quant'] ?? '' ?>">
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendAddContrato" class="btn-success" value="Cadastrar">Cadastrar</button>
            </form>
        </div>
    </div>
</div>
<script>
    function toggleQuantidade() {
        var selectTipo = document.getElementById('tipo').value;
        var divQuantidade = document.getElementById('div_quantidade');
        var inputQuantidade = document.getElementById('quant');
        
        // Se for Hardware (1) ou Software (2), mostra o campo
        if (selectTipo === '1' || selectTipo === '2') {
            divQuantidade.style.display = 'block';
        } else {
            // Se for Serviço (3) ou Vazio, oculta e limpa o valor para não enviar lixo pro banco
            divQuantidade.style.display = 'none';
            inputQuantidade.value = ''; 
        }
    }

    // Executa a função assim que a página carrega 
    // Isso garante que se o formulário der erro e a página recarregar com "Software" já selecionado, o campo de quantidade volta visível
    window.onload = function() {
        toggleQuantidade();
    };
</script>