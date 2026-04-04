<?php
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content"><i class="fa-solid fa-envelope-open-text me-2"></i> Mensagens de Solicitação</span>
            <div class="top-list-right">
                <?php 
                    $total_exibido = count($this->data['listContato']);
                    if ($total_exibido > 0) {
                        echo "<span class='badge-status status-lido' style='min-width: 150px;'>$total_exibido Registros</span>";
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
        </div>

        <table class="table table-hover table-list">
            <thead class="list-head">
                <tr>
                    <th class="list-head-content">ID</th>
                    <th class="list-head-content">Assunto</th>
                    <th class="list-head-content">Cliente</th>
                    <th class="list-head-content table-sm-none">Solicitante</th>
                    <th class="list-head-content table-sm-none text-center">Encaminhar</th>
                    <th class="list-head-content table-sm-none">Data</th>                     
                    <th class="list-head-content table-sm-none text-center">Status</th>
                    <th class="list-head-content text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="list-body">
                <?php
                if (!empty($this->data['listContato'])) {
                    foreach ($this->data['listContato'] as $listContato) {
                        extract($listContato);
                        
                        $class_cor = match ($status_mens) {
                            'Enviado' => 'status-enviado',
                            'Lido' => 'status-lido',
                            'Respondido' => 'status-respondido',
                            default => 'status-default',
                        };

                        $msgZap = "*DOCNET - SUPORTE*%0A*Cliente:* $nome_fantasia_clie%0A*Solicitante:* $nome_mens%0A*Tel/WhatsApp:* $tel_mens%0A*Senha temporária, substitua assim que acessar o sistema: 123456a";
                ?>
                    <tr>
                        <td class="list-body-content"><?php echo $id_mens; ?></td>
                        <td class="list-body-content"><span class="subject-text"><?php echo $assunto_mens; ?></span></td>
                        <td class="list-body-content"><?php echo $nome_fantasia_clie; ?></td>
                        <td class="list-body-content table-sm-none">
                            <small class="d-block fw-bold"><?php echo $nome_mens; ?></small>
                            <small class="text-muted"><?php echo $email_mens; ?></small>
                        </td>

                        <td class="list-body-content table-sm-none text-center">
                            <?php 
                                // Remove tudo que não for número da variavel $tel_mens
                                // Isso evita erros no link caso o numero venha como (xx) xxxxx-xxxx
                                $num_limpo = preg_replace("/[^0-9]/", "", $tel_mens); 
                            ?>
                            
                            <a href="https://wa.me/55<?php echo $num_limpo; ?>?text=<?php echo $msgZap; ?>" target="_blank" class="btn-success btn-sm">
                                <i class="fa-brands fa-whatsapp"></i> Atendimento
                            </a>
                        </td>
                        <!--
                        <td class="list-body-content table-sm-none text-center">
                            <a href="https://wa.me/5571920026348?text=<?php echo $msgZap; ?>" target="_blank" class="btn-success btn-sm">
                                <i class="fa-brands fa-whatsapp"></i> Atendimento
                            </a>
                        </td>
-->
                        <td class="list-body-content table-sm-none"><?php echo date('d/m/Y H:i', strtotime($dia_mens)); ?></td>
                        <td class="list-body-content table-sm-none text-center">
                            <span class="badge-status <?php echo $class_cor; ?>"><?php echo $status_mens; ?></span>
                        </td>
                        <td class="list-body-content text-center">
                            <div class="dropdown-action">
                                <button onclick="actionDropdown(<?php echo $id_mens; ?>)" class="dropdown-btn-action">Ações</button>
                                <div id="actionDropdown<?php echo $id_mens; ?>" class="dropdown-action-item">
                                    <?php
                                    if ($this->data['button']['view_contato']) {
                                        echo "<a href='" . URLADM . "view-contato/index/$id_mens'><i class='fa-solid fa-eye text-info'></i> Visualizar</a>";
                                    }
                                    
                                    // REGRA DE EXIBIÇÃO: Nível 4 + Status Lido
                                    if ($this->data['button']['delete_mensagem']) {
                                        if (($_SESSION['adms_access_level_id'] == 4) && ($status_mens == 'Lido')) {
                                            echo "<a href='" . URLADM . "delete-mensagem/index/$id_mens' class='text-danger' onclick='return confirm(\"Excluir definitivamente?\")'><i class='fa-solid fa-trash'></i> Apagar</a>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
        <div class="content-pagination"><?php echo $this->data['pagination']; ?></div>
    </div>
</div>