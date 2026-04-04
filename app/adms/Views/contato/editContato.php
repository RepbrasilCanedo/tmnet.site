<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

if (isset($this->data['form'][0])) {
    $valorForm = $this->data['form'][0];
}

?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Mensagem</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_contato']) {
                    echo "<a href='" . URLADM . "list-contato/index' class='btn-info'>Listar</a> ";
                }                
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_contato']) {
                        echo "<a href='" . URLADM . "view-contato/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
            <span id="msg"></span>
        </div>
        <div class="content-adm">
            <?php
            if (!empty($this->data['form'])) {
                extract($this->data['form'][0]);
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
            <?php
            }
            ?>
            <form method="POST" action="" id="form-add-contato" class="form-adm">
                
                <?php                
                $id_mens = "";
                if (isset($valorForm['id_mens'])) {
                    $id_mens = $valorForm['id_mens'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id_mens; ?>">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $mensagem_mens = "";
                        if (isset($valorForm['mensagem_mens'])) {
                            $mensagem_mens = $valorForm['mensagem_mens'];
                        }
                        ?>
                        <label class="title-input">Mensagem:</label>
                        <textarea name="mensagem" id="mensagem" class="input-adm" value="<?php echo $mensagem_mens; ?>" required><?php echo $mensagem_mens; ?></textarea>
                    </div>
                </div>

                <button type="submit" name="SendEditContato" class="btn-warning" value="Salvar">Salvar</button>
            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->