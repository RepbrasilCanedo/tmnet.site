<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            Vincular Equipamentos ao Contrato: <?= $this->dados['contrato']['nome'] ?>
        </h6>
        <a href="<?= URL ?>contratos/index" class="btn btn-secondary btn-sm">Voltar</a>
    </div>
    
    <div class="card-body">
        <?php
        if (isset($_SESSION['msg'])) {
            echo $_SESSION['msg'];
            unset($_SESSION['msg']);
        }
        ?>

        <form method="POST" action="">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">#</th>
                            <th>Equipamento</th>
                            <th>Patrimônio/Tag</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(!empty($this->dados['lista_completa'])):
                            foreach ($this->dados['lista_completa'] as $equip): 
                                // Verifica se está no array de vinculados
                                $check = in_array($equip['id'], $this->dados['vinculados']) ? "checked" : "";
                                $class = $check ? "table-success" : "";
                            ?>
                            <tr class="<?= $class ?>">
                                <td class="text-center">
                                    <input type="checkbox" name="equipamentos[]" value="<?= $equip['id'] ?>" <?= $check ?> style="transform: scale(1.5);">
                                </td>
                                <td><?= $equip['nome'] ?></td>
                                <td><?= $equip['patrimonio'] ?? 'N/A' ?></td>
                            </tr>
                        <?php endforeach; 
                        else: ?>
                            <tr><td colspan="3">Nenhum equipamento cadastrado para este cliente.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <hr>
            <button type="submit" name="SendEditEquip" value="Salvar" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-save"></i> Salvar Cobertura do Contrato
            </button>
        </form>
    </div>
</div>