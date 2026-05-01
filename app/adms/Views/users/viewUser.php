<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
$nivelLogado = $_SESSION['adms_access_level_id'];
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Estilos do Perfil do Atleta/Usuário */
    .prof-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
    .prof-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .prof-card h3 { margin-top: 0; color: #0044cc; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; }
    
    .prof-img-box { text-align: center; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .prof-img { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #f4f4f4; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    
    .info-line { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f9f9f9; font-size: 14px; }
    .info-line strong { color: #555; }
    .info-line span { color: #333; text-align: right; }

    .clubes-box { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .clube-badge { background: #eef2fa; border: 1px solid #cce5ff; color: #0044cc; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: flex; align-items: center; gap: 5px; }
    
    .torneio-list { list-style: none; padding: 0; margin: 0; }
    .torneio-item { padding: 12px; background: #f8f9fa; border-left: 4px solid #28a745; margin-bottom: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; }
    .torneio-item.historico { border-left-color: #6c757d; }
    
    @media (max-width: 768px) {
        .prof-grid { grid-template-columns: 1fr; }
        .top-list { flex-direction: column; gap: 15px; align-items: flex-start; }
        .top-list-right { display: flex; flex-wrap: wrap; gap: 5px; width: 100%; }
        .top-list-right a { flex: 1; text-align: center; font-size: 12px; padding: 8px; }
    }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Perfil: <?= $this->data['viewUser'][0]['name_usr'] ?? 'Usuário' ?></span>
            <div class="top-list-right">

                <?php
                if ($this->data['button']['list_users']) {
                    echo "<a href='" . URLADM . "list-atletas/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewUser'])) {
                    $userId = $this->data['viewUser'][0]['id'];
                    
                    if ($userId == $_SESSION['user_id']) {
                        // Se for o próprio atleta no seu perfil, usa o layout premium
                        echo "<a href='" . URLADM . "edit-profile-password/index' class='btn-primary' style='background: #17a2b8;'><i class='fa-solid fa-key'></i> Alterar Senha</a> ";
                    } elseif ($nivelLogado <= 2) {
                        // Se for o Admin da Plataforma alterando a senha de terceiros
                        echo "<a href='" . URLADM . "edit-users-password/index/$userId' class='btn-primary' style='background: #17a2b8;'><i class='fa-solid fa-key'></i> Resetar Senha</a> ";
                    }
                    
                    if ($this->data['button']['edit_users']) {
                        echo "<a href='" . URLADM . "edit-users/index/$userId' class='btn-warning'>Editar Dados</a> ";
                    }
                    if ($this->data['button']['edit_users_image']) {
                        echo "<a href='" . URLADM . "edit-users-image/index/$userId' class='btn-success' style='background: #28a745;'>Mudar Foto</a> ";
                    }
                    if ($this->data['button']['delete_users']) {
                        echo "<a href='" . URLADM . "delete-users/index/$userId' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
                    }
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

        <?php if (!empty($this->data['viewUser'])) { 
            extract($this->data['viewUser'][0]); 
            $clubes = $this->data['viewUser']['clubes_filiado'] ?? [];
            $torneiosAtivos = $this->data['viewUser']['torneios_ativos'] ?? [];
            $torneiosHist = $this->data['viewUser']['torneios_historico'] ?? [];
            $isAtleta = ($id_lev == 14);
        ?>
            
        <div class="prof-grid">
            
            <div>
                <div class="prof-card">
                    <div class="prof-img-box">
                        <?php if ((!empty($imagem)) and (file_exists("app/adms/assets/image/users/$id/$imagem"))): ?>
                            <img src="<?= URLADM ?>app/adms/assets/image/users/<?= $id ?>/<?= $imagem ?>" class="prof-img">
                        <?php else: ?>
                            <img src="<?= URLADM ?>app/adms/assets/image/users/icon_user.png" class="prof-img">
                        <?php endif; ?>
                        
                        <h2 style="margin: 10px 0 5px 0; color: #333; font-size: 18px;"><?= $name_usr ?></h2>
                        <span style="font-size: 13px; color: #666; display: block; margin-bottom: 10px;">@<?= $user ?></span>
                        <span style="font-size: 12px; background: #e0f2f1; color: #0c5460; padding: 4px 10px; border-radius: 12px; font-weight: bold;"><?= $name_lev ?></span>
                        <span style="font-size: 12px; color: <?= $color ?>; border: 1px solid <?= $color ?>; padding: 3px 8px; border-radius: 12px; margin-left: 5px;"><?= $name_sit ?></span>
                    </div>

                    <div class="info-line"><strong>Contato:</strong> <span><?= $telefone ?></span></div>
                    <div class="info-line"><strong>E-mail:</strong> <span><?= $email ?></span></div>
                    <?php if (!empty($data_nascimento)): ?>
                        <div class="info-line"><strong>Nascimento:</strong> <span><?= date('d/m/Y', strtotime($data_nascimento)) ?></span></div>
                    <?php endif; ?>
                    <div class="info-line"><strong>Cadastro:</strong> <span><?= date('d/m/Y', strtotime($created)) ?></span></div>
                </div>

                <?php if ($isAtleta): ?>
                <div class="prof-card" style="margin-top: 20px; border-top: 4px solid #17a2b8;">
                    <h3><i class="fa-solid fa-users"></i> Clubes / Filiações</h3>
                    <?php if (!empty($clubes)): ?>
                        <div class="clubes-box">
                            <?php foreach($clubes as $clube): ?>
                                <span class="clube-badge"><i class="fa-solid fa-shield-halved"></i> <?= $clube['nome_fantasia'] ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p style="font-size: 12px; color: #888;">Atleta ainda não está vinculado a nenhum clube.</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <?php if ($isAtleta): ?>
                <div class="prof-card" style="border-top: 4px solid #ffc107;">
                    <h3><i class="fa-solid fa-bolt"></i> Ficha Técnica</h3>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                        <div style="flex: 1; background: #fff8e1; padding: 15px; border-radius: 8px; text-align: center;">
                            <span style="display: block; font-size: 12px; color: #666; text-transform: uppercase;">Ranking Oficial</span>
                            <span style="display: block; font-size: 24px; font-weight: bold; color: #d39e00;">⭐ <?= $pontuacao_ranking ?> pts</span>
                        </div>
                        <div style="flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 10px;">
                            <div class="info-line" style="border: none; padding: 0;"><strong>Estilo:</strong> <span><?= $estilo_jogo ?: 'Não Informado' ?></span></div>
                            <div class="info-line" style="border: none; padding: 0;"><strong>Mão Dominante:</strong> <span><?= $mao_dominante ?: 'Não Informado' ?></span></div>
                            <div class="info-line" style="border: none; padding: 0;"><strong>Apelido (Mesa):</strong> <span><?= $apelido ?: 'N/A' ?></span></div>
                        </div>
                    </div>
                </div>

                <div class="prof-card" style="margin-top: 20px; border-top: 4px solid #28a745;">
                    <h3><i class="fa-solid fa-trophy"></i> Inscrições Ativas</h3>
                    <?php if (!empty($torneiosAtivos)): ?>
                        <ul class="torneio-list">
                            <?php foreach($torneiosAtivos as $ta): ?>
                                <li class="torneio-item">
                                    <div>
                                        <strong style="color: #333; display: block;"><?= $ta['nome_torneio'] ?></strong>
                                        <small style="color: #888;"><i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($ta['data_evento'])) ?> - <?= $ta['nome_categoria'] ?></small>
                                    </div>
                                    <div>
                                        <?php if ($ta['status_pagamento_id'] == 2 || $ta['status_pagamento_id'] == 3): ?>
                                            <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">APROVADO</span>
                                        <?php else: ?>
                                            <span style="background: #ffc107; color: #333; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">AGUARDA PGTO</span>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="font-size: 13px; color: #888; text-align: center; padding: 15px; border: 1px dashed #ddd;">O atleta não possui inscrições ativas no momento.</p>
                    <?php endif; ?>
                </div>

                <div class="prof-card" style="margin-top: 20px; border-top: 4px solid #6c757d;">
                    <h3><i class="fa-solid fa-clock-rotate-left"></i> Histórico de Participações</h3>
                    <?php if (!empty($torneiosHist)): ?>
                        <ul class="torneio-list">
                            <?php foreach($torneiosHist as $th): ?>
                                <li class="torneio-item historico">
                                    <div>
                                        <strong style="color: #555; display: block;"><?= $th['nome_torneio'] ?></strong>
                                        <small style="color: #888;"><i class="fa-regular fa-calendar"></i> <?= date('d/m/Y', strtotime($th['data_evento'])) ?> - <?= $th['nome_categoria'] ?></small>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p style="font-size: 13px; color: #888; text-align: center; padding: 15px; border: 1px dashed #ddd;">Sem histórico de torneios anteriores.</p>
                    <?php endif; ?>
                </div>
                
                <?php else: ?>
                <div class="prof-card" style="border-top: 4px solid #0044cc; text-align: center; padding: 40px;">
                    <i class="fa-solid fa-laptop-code" style="font-size: 40px; color: #ccc; margin-bottom: 15px;"></i>
                    <h3 style="border: none;">Perfil de Gestão</h3>
                    <p style="color: #666; font-size: 14px;">Este é um perfil administrativo. Acesse os menus laterais para gerir as competições e os clubes da plataforma.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php } ?>
    </div>
</div>