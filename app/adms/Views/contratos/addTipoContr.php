<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Cadastrar Tipo de Contrato</span>
            <div class="top-list-right">
                <?php if (!empty($this->data['button']['list_tipo_contr'])): ?>
                    <a href="<?= URLADM ?>list-tipo-contr/index" class="btn-info">Listar</a>
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
            <form method="POST" action="" id="form-add-tipo-contr" class="form-adm">
                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" 
                               placeholder="Digite o nome do tipo de contrato" 
                               value="<?= $this->data['form']['name'] ?? '' ?>" required>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendAddTipoContr" class="btn-success" value="Cadastrar">Cadastrar</button>
            </form>
        </div>
    </div>
</div>