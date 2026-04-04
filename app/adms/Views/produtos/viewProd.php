<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="dash-wrapper">
    <div class="row mb-4">
        <div class="top-list d-flex justify-content-between align-items-center">
            <span class="title-content">Detalhes do Equipamento</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_prod']) {
                    echo "<a href='" . URLADM . "list-prod/index' class='btn btn-info btn-sm'>Listar</a> ";
                }
                if (!empty($this->data['viewProd'])) {
                    if ($this->data['button']['edit_prod']) {
                        echo "<a href='" . URLADM . "edit-prod/index/" . $this->data['viewProd'][0]['id_prod'] . "' class='btn btn-warning btn-sm'>Editar</a> ";
                    }
                    if ($this->data['button']['delete_prod']) {
                        echo "<a href='" . URLADM . "delete-prod/index/" . $this->data['viewProd'][0]['id_prod'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn btn-danger btn-sm'>Apagar</a> ";
                    }
                }
                ?>
            </div>
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

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <?php
            if (!empty($this->data['viewProd'])) {
                extract($this->data['viewProd'][0]);
                // Armazenamos o ID do produto para usar nos links de laudo abaixo
                $id_produto_atual = $id_prod;
            ?>
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-secondary">Nº Contrato:</dt>
                            <dd class="col-sm-8 fw-bold"><?php echo $id_prod; ?></dd>

                            <dt class="col-sm-4 text-secondary">Nome:</dt>
                            <dd class="col-sm-8"><?php echo $name_prod; ?></dd>

                            <dt class="col-sm-4 text-secondary">Tipo:</dt>
                            <dd class="col-sm-8"><?php echo $name_type; ?></dd>

                            <dt class="col-sm-4 text-secondary">Num. Série:</dt>
                            <dd class="col-sm-8"><?php echo $serie_prod; ?></dd>

                            <dt class="col-sm-4 text-secondary">Modelo:</dt>
                            <dd class="col-sm-8"><?php echo $name_modelo; ?></dd>

                            <dt class="col-sm-4 text-secondary">Marca:</dt>
                            <dd class="col-sm-8"><?php echo $name_mar; ?></dd>

                            <dt class="col-sm-4 text-secondary">Situação:</dt>
                            <dd class="col-sm-8"><?php echo $name_sit; ?></dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <dl class="row mb-0">
                            <dt class="col-sm-4 text-secondary">Empresa:</dt>
                            <dd class="col-sm-8"><?php echo $nome_fantasia_clie; ?></dd>

                            <dt class="col-sm-4 text-secondary">Tipo Contrato:</dt>
                            <dd class="col-sm-8"><?php echo $name_contr_id; ?></dd>

                            <dt class="col-sm-4 text-secondary">Vencimento:</dt>
                            <dd class="col-sm-8"><?php echo date('d/m/Y', strtotime($venc_contr_prod)); ?></dd>

                            <dt class="col-sm-4 text-secondary">Cadastrado:</dt>
                            <dd class="col-sm-8"><?php echo date('d/m/Y H:i', strtotime($created)); ?></dd>

                            <dt class="col-sm-4 text-secondary">Modificado:</dt>
                            <dd class="col-sm-8"><?php if(isset($modified)){echo date('d/m/Y H:i', strtotime($modified));} ?></dd>

                            <dt class="col-sm-4 text-secondary">Obs:</dt>
                            <dd class="col-sm-8 text-muted small"><?php echo $inf_adicionais; ?></dd>
                        </dl>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fs-6 fw-bold text-secondary">Laudos Técnicos (PDF)</h5>
        </div>
        <div class="card-body">
            
            <?php 
            // Mostra o formulário de upload apenas para Suporte (12) ou ADM (4)
            if ($_SESSION['adms_access_level_id'] == 12 || $_SESSION['adms_access_level_id'] == 4): 
            ?>
                <form method="POST" action="" enctype="multipart/form-data" class="row g-3 mb-4 border-bottom pb-3">
                    <div class="col-auto">
                        <label for="laudo_pdf" class="visually-hidden">Selecionar PDF</label>
                        <input type="file" name="laudo_pdf" class="form-control form-control-sm" id="laudo_pdf" accept="application/pdf" required>
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="SendUploadLaudo" value="upload" class="btn btn-success btn-sm">
                            <i class="fa fa-upload"></i> Upload de Laudo
                        </button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nome do Arquivo</th>
                            <th>Data de Envio</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($this->data['listLaudos'])): ?>
                            <?php foreach ($this->data['listLaudos'] as $laudo): ?>
                                <tr>
                                    <td><?php echo $laudo['nome_arquivo']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($laudo['created'])); ?></td>

                                    <td class="text-center">
                                        <a href="<?php echo URLADM . $laudo['caminho']; ?>" target="_blank" class="btn btn-outline-secondary btn-sm">
                                            <i class="fa fa-download"></i> Baixar
                                        </a>

                                        <?php 
                                        // Buscamos o nível de forma segura para não dar erro de "Undefined array key"
                                        $user_level = $_SESSION['adms_access_level_id'] ?? 0;

                                        if ($user_level == 12 || $user_level == 4): 
                                        ?>
                                            <a href="<?php echo URLADM . 'view-prod/index/' . $id_produto_atual . '?del_laudo=' . $laudo['id']; ?>" 
                                            class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Deseja realmente excluir?')">
                                                <i class="fa fa-trash"></i> Apagar
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted p-3">Nenhum laudo arquivado para este produto.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0 fs-6 fw-bold text-secondary">Histórico de Atendimento do Produto</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Status</th>
                            <th>Data</th>
                            <th>Suporte</th>
                            <th>Descrição (Resumo)</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if(isset($this->data['list_table']['listTable']) && !empty($this->data['list_table']['listTable'])){
                            foreach ($this->data['list_table']['listTable'] as $cham) {
                                extract($cham);
                                ?>
                                <tr>
                                    <td><?php echo $nome_sta ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($dt_status_cham)) ?></td>
                                    <td><?php echo $name_usr ?></td>
                                    <td><?php echo mb_strimwidth($inf_cham, 0, 60, "..."); ?></td>
                                    <td class="text-center">
                                        <?php if ($this->data['button']['view_cham']): ?>
                                            <a href="<?php echo URLADM . 'view-cham/index/' . $id; ?>" class="btn btn-outline-primary btn-sm" title="Visualizar Detalhes do Chamado">
                                                <i class="fa fa-eye"></i> Visualizar
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Restrito</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted p-3'>Nenhum chamado encontrado para este equipamento.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>