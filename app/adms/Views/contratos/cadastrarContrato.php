<?php 
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="card mb-4">
    <div class="card-header">
        Cadastro de Contrato
    </div>
    <div class="card-body">
        <form method="POST" action="">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Cliente</label>
                    <select name="cliente_id" class="form-control" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($this->dados['select_clientes'] as $cli): ?>
                            <option value="<?= $cli['id'] ?>"><?= $cli['nome'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Título do Contrato</label>
                    <input type="text" name="nome" class="form-control" placeholder="Ex: Manutenção Mensal 2026" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label>Número/Código</label>
                    <input type="text" name="numero_contrato" class="form-control">
                </div>
                <div class="form-group col-md-4">
                    <label>Início</label>
                    <input type="date" name="data_inicio" class="form-control" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Fim</label>
                    <input type="date" name="data_fim" class="form-control" required>
                </div>
            </div>

            <div class="form-group border p-3 rounded bg-light">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="cobreTudo" name="cobre_todos_equipamentos" value="1">
                    <label class="custom-control-label font-weight-bold" for="cobreTudo">Contrato cobre TODOS os equipamentos?</label>
                    <small class="form-text text-muted">Se desmarcado, você será redirecionado para selecionar os equipamentos específicos.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>

            <input type="submit" name="SendCadContrato" value="Cadastrar" class="btn btn-primary">
        </form>
    </div>
</div>