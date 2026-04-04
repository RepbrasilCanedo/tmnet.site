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
//var_dump($valorForm);
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Editar Aviso</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_aviso']) {
                    echo "<a href='" . URLADM . "list-aviso/index' class='btn-info'>Listar</a> ";
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
            <hr>
            <span>DICAS ÚTEIS</span><br>
            <?php 
            $texto_com_tag = "Use <strong>texto em negrito</strong> para colocar a palavra ou a frase em negrito. Use para quebra de linha <br> no final da frase caso queira mudar de linha. Use <hr> se quer colocar uma linha visivel."; 
            // 1. Mostrar a tag <br> como texto (sem quebra de linha)
            $texto_codificado = htmlspecialchars($texto_com_tag);
            echo $texto_codificado;
            ?>
            <hr>
            
            <form method="POST" action="" id="form-edit-empresas" class="form-adm">
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
                        <label class="title-input">Titulo Aviso<span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="input-adm" placeholder="Título do Aviso" value="<?php echo $name; ?>" required>
                    </div>

                    <div class="column">
                        <?php
                        $texto = "";
                        if (isset($valorForm['texto'])) {
                            $texto = $valorForm['texto'];
                        }
                        ?>
                        <label class="title-input">Texto do aviso<span class="text-danger">*</span></label>
                        <textarea name="texto" id="texto" rows="8" cols="50" class="input-adm" maxlength="480" placeholder="Texto do Aviso."required><?php echo $texto; ?></textarea>
                    </div>
                </div>

                <div class="row-input">

                    <div class="column">
                        <?php
                        $tit_aviso = "";
                        if (isset($valorForm['tit_aviso'])) {
                            $tit_aviso = $valorForm['tit_aviso'];
                        }
                        ?>
                        <label class="title-input">Título do Alerta</span></label>
                        <input type="text" name="tit_aviso" id="tit_aviso" class="input-adm" placeholder="Título do Alerta" value="<?php echo $tit_aviso; ?>">
                    </div>
                    <div class="column">
                        <?php
                        $aviso = "";
                        if (isset($valorForm['aviso'])) {
                            $aviso = $valorForm['aviso'];
                        }
                        ?>
                        <label class="title-input">Texto do Alerta</label>
                        <textarea name="aviso" id="aviso" rows="8" cols="50" class="input-adm" maxlength="480" placeholder="Texto do Alerta."><?php echo $aviso; ?></textarea>
                    </div>
                </div>

                <p class="text-danger mb-5 fs-6">* Campo Obrigatório</p>

                <button type="submit" name="SendEditAviso" class="btn-warning" value="Salvar">Alterar</button>

            </form>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->