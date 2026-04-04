<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

//echo('<pre>');var_dump($this->data);echo('</pre>');
?>


<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Relatório de Clientes</span><br>
        </div>

        <div class="top-list">
            <form method="POST" action="" target="_blank">
                <div class="row-input-search">
                            <div class="column">
                                <label class="title-input">Cidade:</label>
                                <input type="text" name="search_cidade" id="search_cidade" class="input-search" placeholder="Pesquisar pela cidade...">
                            </div>


                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchClie" class="btn-warning" value="Pesquisar" target="_blank">Gerar Pdf</button>
                    </div>
            </form>

        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->