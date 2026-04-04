<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<!-- Inicio do conteudo do administrativo -->
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Detalhes do Colaborador(a)</span>
            <div class="top-list-right">
                <?php
                if ($this->data['button']['list_users']) {
                    echo "<a href='" . URLADM . "list-users/index' class='btn-info'>Listar</a> ";
                }
                if (!empty($this->data['viewUser'])) {
                    if ($this->data['button']['edit_users']) {
                        echo "<a href='" . URLADM . "edit-users/index/" . $this->data['viewUser'][0]['id'] . "' class='btn-warning'>Editar</a> ";
                    }
                    if ($this->data['button']['edit_users_image']) {
                        echo "<a href='" . URLADM . "edit-users-image/index/" . $this->data['viewUser'][0]['id'] . "' class='btn-warning'>Editar Imagem</a> ";
                    }
                    if ($this->data['button']['delete_users']) {
                        echo "<a href='" . URLADM . "delete-users/index/" . $this->data['viewUser'][0]['id'] . "' onclick='return confirm(\"Tem certeza que deseja excluir este registro?\")' class='btn-danger'>Apagar</a> ";
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
        </div>

        <div class="content-adm">
            <?php
            if (!empty($this->data['viewUser'])) {
                extract($this->data['viewUser'][0]);
            ?>
                <div class="view-det-adm">
                    <span class="view-adm-title">Foto: </span>
                    <span class="view-adm-info">
                        <?php
                        if ((!empty($imagem)) and (file_exists("app/adms/assets/image/users/$id/$imagem"))) {
                            echo "<img src='" . URLADM . "app/adms/assets/image/users/$id/$imagem' width='100' height='100'><br><br>";
                        } else {
                            echo "<img src='" . URLADM . "app/adms/assets/image/users/icon_user.png' width='100' height='100'><br><br>";
                        }
                        ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">ID: </span>
                    <span class="view-adm-info"><?php echo $id; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Nome: </span>
                    <span class="view-adm-info"><?php echo $name_usr; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">E-mail: </span>
                    <span class="view-adm-info"><?php echo $email; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Usuário: </span>
                    <span class="view-adm-info"><?php echo $user; ?></span>
                </div>
                <?php
                        if ($data_nascimento <> '') { ?>
                            <div class="view-det-adm">
                                <span class="view-adm-title">Data Nascimento: </span>
                                <span class="view-adm-info"><?php echo date('d/m/Y', strtotime($data_nascimento)); ?></span>
                            </div>
                <?php } ?>
                

                <div class="view-det-adm">
                    <span class="view-adm-title">Situação do Usuário: </span>
                    <span class="view-adm-info">
                        <?php echo "<span style='color: $color;'>$name_sit</span>"; ?>
                    </span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Tel: </span>
                    <span class="view-adm-info"><?php echo "$tel_1 " . " / " . " $tel_2 "; ?></span>
                </div>

                <div class="view-det-adm">
                    <span class="view-adm-title">Empresa: </span>
                    <span class="view-adm-info"><?php echo  $razao_social_emp ." -- ". $nome_fantasia_emp; ?></span>
                </div>                

                <div class="view-det-adm">
                    <span class="view-adm-title">Nível de Acesso: </span>
                    <span class="view-adm-info"><?php echo  $name_lev; ?></span>
                </div>  
                <?php if ($name_lev== 'Atleta'){?>                                  

                    <div class="view-det-adm">
                        <span class="view-adm-title">Estilo de Jogo </span>
                        <span class="view-adm-info"><?php echo  $estilo_jogo; ?></span>
                    </div>               

                    <div class="view-det-adm">
                        <span class="view-adm-title">Não Dominante </span>
                        <span class="view-adm-info"><?php echo  $mao_dominante; ?></span>
                    </div>                              

                    <div class="view-det-adm">
                        <span class="view-adm-title">Pontuação no Ranking </span>
                        <span class="view-adm-info"><?php echo  $pontuacao_ranking; ?></span>
                    </div>
                <?php } ?>

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