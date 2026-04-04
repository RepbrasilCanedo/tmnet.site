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
/*
echo "<pre>";
var_dump($valorForm);
echo "</pre>";*/
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Contrato</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_contr']) {
                    echo "<a href='" . URLADM . "list-contr/index' class='btn-info'>Listar</a> ";
                }
                if (isset($valorForm['id'])) {
                    if ($this->data['button']['view_contr']) {
                        echo "<a href='" . URLADM . "view-contr/index/" . $valorForm['id'] . "' class='btn-primary'>Visualizar</a><br><br>";
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
            <form method="POST" action="" id="form-edit-contr" class="form-adm">
                <?php
                $id_cont = "";
                if (isset($valorForm['id_cont'])) {
                    $id_cont = $valorForm['id_cont'];
                }
                ?>
                <input type="hidden" name="id" id="id" value="<?php echo $id_cont; ?>">

                <div class="row-input">

                    <div class="column">
                        <?php
                        $name_cont = "";
                        if (isset($valorForm['name_cont'])) {
                            $name_cont = $valorForm['name_cont'];
                        }
                        ?>
                        <label class="title-input">Tipo Contrato:<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Tipo Contrato" value="<?php echo $name_cont; ?>" required>

                    </div>
                    <div class="column">                        
                        <?php
                        $situacao = "";
                        if (isset($valorForm['situacao'])) {
                            $situacao = $valorForm['situacao'];
                        }
                        ?>
                        <label class="title-input">Situação do Contrato:<span class="text-danger">*</span></label>
                        <select name="sit_cont" id="sit_cont" class="input-adm" required>
                            <option value="">Selecione</option>
                            <?php
                            foreach ($this->data['select']['name_sit'] as $sitContr) {
                                extract($sitContr);
                                if (isset($valorForm['situacao']) and $valorForm['situacao'] == $name_sit) {
                                    echo "<option value='$id_sit' selected>$name_sit </option>";
                                } else {
                                    echo "<option value='$id_sit'>$name_sit </option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <p class="text-danger mb-2 fs-6">* Campo Obrigatório</p>
                <button type="submit" name="SendEditContr" class="btn-success" value="Cadastrar">Salvar</button>
            </form>
        </div>


    </div>
</div>
<!-- Fim do conteudo do administrativo -->