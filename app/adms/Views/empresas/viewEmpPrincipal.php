<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
//var_dump($this->data);
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Dados da Empresa</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_emp_principal']) {
                    echo "<a href='" . URLADM . "list-emp-principal/index' class='btn-info'>Listar</a> ";
                }
                if ($this->data['button']['edit_emp_principal']) {
                    echo "<a href='" . URLADM . "edit-emp-principal/index/". $this->data['viewEmpPrincipal'][0]['id'] ."'class='btn-warning'>Editar</a>";
                }
                if ($this->data['button']['edit_profile_logo']) {
                    echo "<a href='" . URLADM . "edit-profile-logo/index/". $this->data['viewEmpPrincipal'][0]['id'] ."' class='btn-success'>Editar Logo</a>";
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
            if (!empty($this->data['viewEmpPrincipal'])) {
                extract($this->data['viewEmpPrincipal'][0]);
            ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">Logo: </span>
                    <span class="view-adm-info">
                        <?php
                        if ((!empty($logo_emp)) and (file_exists("app/adms/assets/image/logo/clientes/$id/$logo_emp"))) {
                            echo "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/$id/$logo_emp' width='100' height='100'><br><br>";
                        } else {
                            echo "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/icon_user.png' width='100' height='100'><br><br>";
                        }
                        
                        ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Razão Social: </span>
                    <span class="view-adm-info"><?php echo $razao_social; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome de Fantasia: </span>
                    <span class="view-adm-info"><?php echo $nome_fantasia; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cnpj: </span>
                    <span class="view-adm-info"><?php echo $cnpj; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cep: </span>
                    <span class="view-adm-info"><?php echo $cep; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Logradouro: </span>
                    <span class="view-adm-info"><?php echo $logradouro; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Bairro: </span>
                    <span class="view-adm-info"><?php echo $bairro; ?></span>

                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">cidade: </span>
                    <span class="view-adm-info"><?php echo $cidade; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Uf: </span>
                    <span class="view-adm-info"><?php echo $uf; ?></span>

                </div>
                <div class="view-det-adm">
                    <span class="view-adm-title">Contato: </span>
                    <span class="view-adm-info"><?php echo $contato; ?></span>

                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Telefone: </span>
                    <span class="view-adm-info"><?php echo $telefone; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">E-mail: </span>
                    <span class="view-adm-info"><?php echo $email; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação: </span>
                    <span class="view-adm-info"><?php echo $name_sit; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Cadastrado: </span>
                    <span class="view-adm-info"><?php echo date('d/m/Y H:i:s', strtotime($created)); ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Editado: </span>
                    <span class="view-adm-info">
                        <?php
                        if (!empty($modified)) {
                            echo date('d/m/Y H:i:s', strtotime($modified));
                        } ?>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<!-- Fim do conteudo do administrativo -->