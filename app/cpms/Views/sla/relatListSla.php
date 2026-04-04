<?php

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

if (isset($this->data['form'])) {
    $valorForm = $this->data['form'];
}

//echo('<pre>');var_dump($valorForm);echo('</pre>');
?>


<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Relatório dos Slas do Ticket</span><br>
        </div>

        <div class="top-list">
            <form method="POST" action=""  target="_blank";>
            
                <div class="row-input-search">

                    <!--4: Cliente Administrador -->   
                    <?php if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] <=2)) { ?>                           
                            
                        <div class="column">
                               <?php
                               $searchTicket = "";
                               if (isset($valorForm['search_ticket'])) {
                                   $searchTicket = $valorForm['search_ticket'];
                               }
                               ?>
                               <label class="title-input">Número Ticket:</label>
                               <input type="number" name="search_ticket" id="search_ticket" class="input-adm" placeholder="Todos" value="<?= $searchTicket ?>">
                        </div>

                            <div class="column">
                                <label class="title-input">Clientes:</label>
                                <select name="search_empresa" id="search_empresa" class="input-adm">
                                    <option value="">Todas</option>
                                    <?php
                                    foreach ($this->data['select']['nome_clie'] as $nome_clie) {
                                        extract($nome_clie);
                                        if (isset($valorForm['search_empresa']) and $valorForm['search_empresa'] == $id) {
                                            echo "<option value='$id' selected>$nome_fantasia</option>";
                                        } else {
                                            echo "<option value='$id'>$nome_fantasia</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="column">
                                <label class="title-input">Suporte:</label>
                                <select name="search_suporte" id="search_suporte" class="input-adm">
                                    <option value="">Todos</option>
                                    <?php
                                    foreach ($this->data['select']['nome_sup'] as $searchSuporte) {
                                        extract($searchSuporte);
                                        if (isset($valorForm['search_suporte']) and $valorForm['search_suporte'] == $id) {
                                            echo "<option value='$id' selected>$name</option>";
                                        } else {
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="column">
                                <label class="title-input">Tipo do Sla do Ticket:</label>
                                    <select name="search_tipo" id="search_tipo" class="input-adm">
                                    <option value="">Todas</option>
                                    <?php
                                    foreach ($this->data['select']['nome_tipo'] as $nome_tipo) {
                                        extract($nome_tipo);
                                        if (isset($valorForm['search_tipo']) and $valorForm['search_tipo'] == $id) {
                                            echo "<option value='$id' selected>$name</option>";
                                        } else {
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="column">
                                <?php
                                $search_date_start = "";
                                if (isset($valorForm['search_date_start'])) {
                                    $search_date_start = $valorForm['search_date_start'];
                                }
                                ?>
                                <label class="title-input-search">Data Inicio: </label>
                                <input type="date" name="search_date_start" id="search_date_start" class="input-search" value="<?php echo $search_date_start; ?>">
                            </div>

                            <div class="column">
                                <?php
                                $search_date_end = "";
                                if (isset($valorForm['search_date_end'])) {
                                    $search_date_end = $valorForm['search_date_end'];
                                }
                                ?>
                                <label class="title-input-search">Data Final: </label>
                                <input type="date" name="search_date_end" id="search_date_end" class="input-search" value="<?php echo $search_date_end; ?>">
                            </div>

                            <div class="column">
                                <label class="title-input">Status Anterior do Ticket:</label>
                                <select name="search_status_anterior" id="search_status_anterior" class="input-adm">
                                    <option value="">Todos</option>
                                    <?php
                                    foreach ($this->data['select']['nome_status'] as $status) {
                                        extract($status);
                                        if (isset($valorForm['search_status_anterior']) and $valorForm['search_status_anterior'] == $id) {
                                            echo "<option value='$id' selected>$name</option>";
                                        } else {
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="column">
                                <label class="title-input">Status Atual do Ticket:</label>
                                <select name="search_status" id="search_status" class="input-adm">
                                    <option value="">Todos</option>
                                    <?php
                                    foreach ($this->data['select']['nome_status'] as $status) {
                                        extract($status);
                                        if (isset($valorForm['search_status']) and $valorForm['search_status'] == $id) {
                                            echo "<option value='$id' selected>$name</option>";
                                        } else {
                                            echo "<option value='$id'>$name</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                    <?php } ?>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchSla" class="btn-warning" value="Pesquisar">Gerar Pdf</button>
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