<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

// Lógica rápida para traduzir o tipo (1, 2, 3) para texto na visualização
$categoriaTexto = "Não definido";
if (isset($this->data['viewContrato']['tipo'])) {
    if ($this->data['viewContrato']['tipo'] == 1) $categoriaTexto = "Hardware";
    elseif ($this->data['viewContrato']['tipo'] == 2) $categoriaTexto = "Software";
    elseif ($this->data['viewContrato']['tipo'] == 3) $categoriaTexto = "Serviço";
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes do Contrato</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['list_contratos'])): ?>
                    <a href="<?= URLADM ?>list-contratos/index" class="btn-info">Listar</a>
                <?php endif; ?>
                
                <?php if (!empty($this->data['button']['edit_contratos']) && !empty($this->data['viewContrato']['id'])): ?>
                    <a href="<?= URLADM ?>edit-contratos/index/<?= $this->data['viewContrato']['id'] ?>" class="btn-warning">Editar</a>
                <?php endif; ?>
                
                <?php if (!empty($this->data['button']['delete_contratos']) && !empty($this->data['viewContrato']['id'])): ?>
                    <a href="<?= URLADM ?>delete-contratos/index/<?= $this->data['viewContrato']['id'] ?>" class="btn-danger" onclick="return confirm('Tem certeza que deseja apagar?')">Apagar</a>
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
            <?php if (!empty($this->data['viewContrato'])): ?>
                
                <div class="view-det-list">
                    <span class="view-det-title">Nome/Descrição:</span>
                    <span class="view-det-info"><?= $this->data['viewContrato']['name'] ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Cliente:</span>
                    <span class="view-det-info"><?= $this->data['viewContrato']['cliente_nome'] ?? 'Cliente não encontrado' ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Tipo de Contrato (Docnet):</span>
                    <span class="view-det-info"><?= $this->data['viewContrato']['tipo_nome'] ?? 'Tipo não encontrado' ?></span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Categoria:</span>
                    <span class="view-det-info"><?= $categoriaTexto ?></span>
                </div>

                <?php if (in_array($this->data['viewContrato']['tipo'], [1, 2])): ?>
                <div class="view-det-list">
                    <span class="view-det-title">Quantidade:</span>
                    <span class="view-det-info"><?= $this->data['viewContrato']['quant'] ?? '0' ?></span>
                </div>
                <?php endif; ?>

                <div class="view-det-list">
                    <span class="view-det-title">Período:</span>
                    <span class="view-det-info">
                        <?= !empty($this->data['viewContrato']['inicio_contr']) ? date('d/m/Y', strtotime($this->data['viewContrato']['inicio_contr'])) : '-' ?> 
                        até 
                        <?= !empty($this->data['viewContrato']['final_contr']) ? date('d/m/Y', strtotime($this->data['viewContrato']['final_contr'])) : '-' ?>
                    </span>
                </div>

                <div class="view-det-list">
                    <span class="view-det-title">Status:</span>
                    <span class="view-det-info"><?= $this->data['viewContrato']['sit_nome'] ?? 'Status não encontrado' ?></span>
                </div>

                <div style="margin-top: 50px; border-top: 2px solid #ccc; padding-top: 20px;">
                    <h3 class="title-content" style="color: #444; margin-bottom: 20px;">
                        <i class="fa-solid fa-file-pdf"></i> Anexos e Arquivos Físicos (PDF)
                    </h3>

                    <div style="background-color: #f4f6f9; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                        <form method="POST" action="" enctype="multipart/form-data" style="display: flex; align-items: flex-end; gap: 15px; flex-wrap: wrap;">
                            <div style="flex: 1; min-width: 250px;">
                                <label class="title-input">Selecionar Arquivo PDF:</label>
                                <input type="file" name="image" class="input-adm" accept=".pdf" required style="padding-bottom: 0;">
                            </div>
                            <div>
                                <button type="submit" name="SendAddAnexo" class="btn-success" value="Anexar Arquivo" style="padding: 10px 20px; cursor: pointer;">
                                    <i class="fa-solid fa-upload"></i> Enviar Anexo
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php if (!empty($this->data['viewAnexos'])): ?>
                        <table class="table table-hover table-list">
                            <thead class="list-head">
                                <tr>
                                    <th class="list-head-content">ID</th>
                                    <th class="list-head-content">Nome do Arquivo</th>
                                    <th class="list-head-content">Data de Envio</th>
                                    <th class="list-head-content">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="list-body">
                                <?php foreach ($this->data['viewAnexos'] as $anexo): 
                                    $caminhoPdf = URLADM . "app/adms/assets/arquivos/contratos/" . $this->data['viewContrato']['id'] . "/" . $anexo['image'];
                                ?>
                                    <tr>
                                        <td class="list-body-content"><?= $anexo['id'] ?></td>
                                        <td class="list-body-content">
                                            <i class="fa-solid fa-file-pdf" style="color: #e25822; margin-right: 5px;"></i> 
                                            <?= htmlspecialchars($anexo['image']) ?>
                                        </td>
                                        <td class="list-body-content">
                                            <?= date('d/m/Y H:i', strtotime($anexo['created'])) ?>
                                        </td>
                                        <td class="list-body-content">
                                            <a href="<?= $caminhoPdf ?>" target="_blank" class="btn-info" style="padding: 4px 8px; font-size: 12px; margin-right: 5px;">Visualizar</a>
                                            <a href="<?= $caminhoPdf ?>" download class="btn-primary" style="padding: 4px 8px; font-size: 12px; margin-right: 5px;">Download</a>
                                            
                                            <?php if (isset($_SESSION['adms_access_level_id']) && $_SESSION['adms_access_level_id'] == 4): ?>
                                                <a href="<?= URLADM ?>delete-anexo-contrato/index/<?= $anexo['id'] ?>" onclick="return confirm('Tem certeza que deseja apagar este anexo permanentemente? O arquivo físico será destruído.')" class="btn-danger" style="padding: 4px 8px; font-size: 12px;">Apagar</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="alert-info" style="margin-top: 15px;">Nenhum arquivo físico anexado a este contrato até o momento.</p>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <p class="alert-danger">Erro: Nenhum detalhe encontrado para este registo.</p>
            <?php endif; ?>
        </div>
    </div>
</div>