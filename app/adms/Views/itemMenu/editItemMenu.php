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
            <span class="title-content">Editar Item de Menu</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_item_menu']) {
                    echo "<a href='" . URLADM . "list-item-menu/index' class='btn-info'>Listar</a> ";
                }                
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_item_menu']) {
                        echo "<a href='" . URLADM . "view-item-menu/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
            <form method="POST" action="" id="form-add-item-menu" class="form-adm">
                <?php
                $id = "";
                if (isset($valorForm['id'])) {
                    $id = $valorForm['id'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

                <div class="row-input">
                    <div class="column">
                        <?php
                        $name = "";
                        if (isset($valorForm['name'])) {
                            $name = $valorForm['name'];
                        }
                        ?>
                        <label class="title-input">Nome:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Digite o nome do item de menu" value="<?php echo $name; ?>"  required>
                    </div>
                </div>

                <div class="row-input">
                    <div class="column">
                        <?php
                        $icon = "";
                        if (isset($valorForm['icon'])) {
                            $icon = $valorForm['icon'];
                        }
                        ?>
                        <label class="title-input">Ícone:<span class="text-danger">*</span></label>
                        <input type="text" name="icon" id="icon" class="input-adm" placeholder="Digite o ícone, ícone da biblioteca Fontawesome" value="<?php echo $icon; ?>"  required>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditItemMenu" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->