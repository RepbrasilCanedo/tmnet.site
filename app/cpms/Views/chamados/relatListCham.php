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
            <span class="title-content">Relatório de Tickets</span><br>
        </div>

        <div class="top-list">
            <form method="POST" action="" target="_blank">
                <div class="row-input-search">

                    <?php if ($_SESSION['adms_access_level_id'] > 2) { ?>
                        <!--4 ou 12: Usuario adm e suporte -->
                        <?php if (($_SESSION['adms_access_level_id'] == 4) or($_SESSION['adms_access_level_id'] == 12)){ ?>                           

                            <div class="column">
                                <label class="title-input">Cliente:</label>
                                <select name="search_empresa" id="search_empresa" class="input-adm">
                                    <option value="">Selecione um Cliente</option>
                                    <?php
                                    foreach ($this->data['select']['nome_emp'] as $nome_emp) {
                                        extract($nome_emp);
                                        if (isset($valorForm['search_empresa']) and $valorForm['search_empresa'] == $id_emp) {
                                            echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                        } else {
                                            echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="column">
                                <label class="title-input">Status do Ticket:</label>
                                <select name="search_status" id="search_status" class="input-adm">
                                    <option value="">Selecione um Status</option>
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

                            <div class="column">
                                <label class="title-input">Tipo do Ticket:</label>
                                <select name="search_tipo" id="search_tipo" class="input-adm">
                                    <option value="">Selecione um Tipo</option>
                                    <option value="Telefonico">Telefônico</option>
                                    <option value="Remoto">Remoto</option>
                                    <option value="Presencial">Presencial</option>
                                    <option value="Apresentacao">Apresentação Negócios</option>
                                    <option value="Outros">Outros</option>
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
                                <label class="title-input">Colaborador(a):</label>
                                    <select name="search_tec_suporte" id="search_tec_suporte" class="input-emp" style="width: 100%">
                                        <option value="">Selecione um Colaborador</option>
                                        <?php
                                        foreach ($this->data['select']['nome_emp_user'] as $nome_emp_user) {
                                            extract($nome_emp_user);
                                            if (isset($valorForm['search_tec_suporte']) and $valorForm['search_tec_suporte'] == $id) {
                                                echo "<option value='$id' selected>$name</option>";
                                            } else {
                                                echo "<option value='$id'>$name</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                        </div>

                        <?php } ?>

                    <?php } else { ?>

                        <div class="column">
                            <label class="title-input">Cliente:</label>
                            <select name="search_empresa" id="search_empresa" class="input-emp" style="width: 100%">
                                <option value="">Todas</option>
                                <?php
                                foreach ($this->data['select']['nome_emp'] as $nome_emp) {
                                    extract($nome_emp);
                                    if (isset($valorForm['search_empresa']) and $valorForm['search_empresa'] == $id_emp) {
                                        echo "<option value='$id_emp' selected>$nome_fantasia_emp</option>";
                                    } else {
                                        echo "<option value='$id_emp'>$nome_fantasia_emp</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="column">
                            <label class="title-input">Status do Ticket:</label>
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

                        <div class="column">
                            <label class="title-input">Tipo do Ticket:</label>
                            <select name="search_tipo" id="search_tipo" class="input-adm">
                                <option value="">Todos</option>
                                <option value="Telefonico">Telefônico</option>
                                <option value="Remoto">Remoto</option>
                                <option value="Presencial">Presencial</option>
                                    <option value="Apresentacao">Apresentação Negócios</option>
                                <option value="Outros">Outros</option>
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

                    <?php } ?>

                    <div class="column margin-top-search">
                        <button type="submit" name="SendSearchCham" class="btn-warning" value="Pesquisar">Gerar Pdf</button>
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