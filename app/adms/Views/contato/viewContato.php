<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes da Mensagem</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_contato']) {
                    echo "<a href='" . URLADM . "list-contato/index' class='btn-info'>Listar</a> ";
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

        <div class="content-adm">
            <?php
            if (!empty($this->data['viewContato'])) {
                extract($this->data['viewContato'][0]);
            ?>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id_mens; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Assunto: </span>
                    <span class="view-adm-info"><?php echo $assunto_mens; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cliente: </span>
                    <span class="view-adm-info"><?php echo "$nome_fantasia_clie";?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo "$nome_mens";?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">E-mail: </span>
                    <span class="view-adm-info"><?php echo $email_mens; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Telefone: </span>
                    <span class="view-adm-info"><?php echo $tel_mens; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Mensagem: </span>
                    <span class="view-adm-info"><?php echo "$mensagem_mens";?></span>
                </div>                

                <div class="view-det-adm">
                    <span class="view-adm-title">Status: </span>                    
                    <span class="view-adm-info"><?php echo "$status_mens";?>  dia: <?php echo date('d/m/Y H:i:s', strtotime($dia_mens)); ?></span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->