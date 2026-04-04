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
            <span class="title-content">Editar Item de Menu da Página</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_permission']) {
                    echo "<a href='" . URLADM . "list-permission/index?level={$this->data['btnLevel']}' class='btn-info'>Listar</a> ";
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
            <form method="POST" action="" id="form-edit-page-menu" class="form-adm">
                <?php
                $id = "";
                if (isset($valorForm['id'])) {
                    $id = $valorForm['id'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">

                <?php
                $name_page = "";
                if (isset($valorForm['name_page'])) {
                    $name_page = $valorForm['name_page'];
                }
                ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">Página: </span>
                    <span class="view-adm-info"><?php echo $name_page; ?></span>
                </div>

                <div class="row-input">
                    <div class="column">
                        <label class="title-input">Item de Menu:<span class="text-danger">*</span></label>
                        <select name="adms_items_menu_id" id="adms_items_menu_id" class="input-adm" >
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['itm'] as $itmMenu) {
                                extract($itmMenu);
                                if (isset($valorForm['adms_items_menu_id']) and $valorForm['adms_items_menu_id'] == $id_itm) {
                                    echo "<option value='$id_itm' selected>$name_itm</option>";
                                } else {
                                    echo "<option value='$id_itm'>$name_itm</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-4">* Campo Obrigatório</p>

                <button type="submit" name="SendEditPageMenu" class="btn-warning" value="Salvar">Salvar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->