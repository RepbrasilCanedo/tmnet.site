<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

// Facilita a chamada da variável principal
$cliente = $this->data['viewEmpresas'] ?? [];
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes do Cliente</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['list_empresas'])): ?>
                    <a href="<?= URLADM ?>list-empresas/index" class="btn-info">Listar</a>
                <?php endif; ?>
                
                <?php if (!empty($cliente)): ?>
                    <?php if (!empty($this->data['button']['edit_empresas'])): ?>
                        <a href="<?= URLADM ?>edit-empresas/index/<?= $cliente['id'] ?>" class="btn-warning">Editar</a>
                    <?php endif; ?>
                    
                    <?php if (!empty($this->data['button']['delete_empresas'])): ?>
                        <a href="<?= URLADM ?>delete-empresas/index/<?= $cliente['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')" class="btn-danger">Apagar</a>
                    <?php endif; ?>
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
            <?php if (!empty($cliente)): ?>
                
                <h3 class="title-content" style="margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Dados Principais</h3>
                
                <div class="view-det-list">
                    <span class="view-det-title">ID:</span>
                    <span class="view-det-info"><?= $cliente['id'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Razão Social:</span>
                    <span class="view-det-info"><?= $cliente['razao_social'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Nome Fantasia:</span>
                    <span class="view-det-info"><?= $cliente['nome_fantasia'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">CNPJ/CPF:</span>
                    <span class="view-det-info"><?= $cliente['cnpjcpf'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Status:</span>
                    <span class="view-det-info"><?= $cliente['name_sit'] ?></span>
                </div>

                <h3 class="title-content" style="margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Endereço</h3>
                
                <div class="view-det-list">
                    <span class="view-det-title">CEP:</span>
                    <span class="view-det-info"><?= $cliente['cep'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Logradouro:</span>
                    <span class="view-det-info"><?= $cliente['logradouro'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Bairro:</span>
                    <span class="view-det-info"><?= $cliente['bairro'] ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Cidade / UF:</span>
                    <span class="view-det-info"><?= $cliente['cidade'] ?> / <?= $cliente['uf'] ?></span>
                </div>

                <h3 class="title-content" style="margin-top: 30px; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Informações do Sistema</h3>
                
                <div class="view-det-list">
                    <span class="view-det-title">Cadastrado em:</span>
                    <span class="view-det-info"><?= !empty($cliente['created']) ? date('d/m/Y H:i:s', strtotime($cliente['created'])) : '-' ?></span>
                </div>
                <div class="view-det-list">
                    <span class="view-det-title">Última Edição:</span>
                    <span class="view-det-info"><?= !empty($cliente['modified']) ? date('d/m/Y H:i:s', strtotime($cliente['modified'])) : '-' ?></span>
                </div>

                <h3 class="title-content" style="margin-top: 40px; margin-bottom: 15px; border-bottom: 2px solid #007bff; padding-bottom: 5px; color: #007bff;">
                    <i class="fa-solid fa-file-contract"></i> Contratos Atrelados a este Cliente
                </h3>

                <?php if (!empty($this->data['viewContratos'])): ?>
                    <table class="table table-hover table-list" style="margin-top: 15px;">
                        <thead class="list-head">
                            <tr>
                                <th class="list-head-content">ID</th>
                                <th class="list-head-content">Descrição</th>
                                <th class="list-head-content table-sm-none">Tipo (Docnet)</th>
                                <th class="list-head-content table-sm-none">Categoria</th>
                                <th class="list-head-content table-sm-none">Vigência</th>
                                <th class="list-head-content table-sm-none">Status</th>
                                <th class="list-head-content">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="list-body">
                            <?php foreach ($this->data['viewContratos'] as $contrato): 
                                // Traduz o Tipo (1, 2, 3)
                                $categoriaTexto = "Não definido";
                                if ($contrato['tipo'] == 1) $categoriaTexto = "Hardware";
                                elseif ($contrato['tipo'] == 2) $categoriaTexto = "Software";
                                elseif ($contrato['tipo'] == 3) $categoriaTexto = "Serviço";
                            ?>
                                <tr>
                                    <td class="list-body-content"><?= $contrato['id'] ?></td>
                                    <td class="list-body-content"><?= htmlspecialchars($contrato['name']) ?></td>
                                    <td class="list-body-content table-sm-none"><?= htmlspecialchars($contrato['tipo_nome'] ?? '-') ?></td>
                                    <td class="list-body-content table-sm-none"><?= $categoriaTexto ?></td>
                                    
                                    <td class="list-body-content table-sm-none">
                                        <?= !empty($contrato['inicio_contr']) ? date('d/m/Y', strtotime($contrato['inicio_contr'])) : '-' ?> 
                                        a 
                                        <?= !empty($contrato['final_contr']) ? date('d/m/Y', strtotime($contrato['final_contr'])) : '-' ?>
                                    </td>
                                    
                                    <td class="list-body-content table-sm-none"><?= htmlspecialchars($contrato['sit_nome'] ?? '-') ?></td>
                                    
                                    <td class="list-body-content">
                                        <a href="<?= URLADM ?>view-contratos/index/<?= $contrato['id'] ?>" class="btn-primary" style="padding: 4px 8px; font-size: 12px; border-radius: 4px;">Ver Contrato</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="background-color: #f8f9fa; padding: 20px; border-left: 4px solid #17a2b8; border-radius: 4px;">
                        <p style="margin: 0; color: #555;">Este cliente ainda não possui nenhum contrato cadastrado no sistema.</p>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <p class="alert-warning">Erro ao carregar detalhes do cliente.</p>
            <?php endif; ?>
        </div>
    </div>
</div>