<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
//var_dump($this->data); die;
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content"> Atletas:  <?php echo $this->data['listAtletas'][0]['nome_fantasia'] . '  - ' . $this->data['listAtletas'][0]['razao_social'] ?></span> 

        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <table class="list-table">
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Nome / Apelido</th>
                    <th>Estilo</th>
                    <th>Mão Dominante</th>
                    <th>Pontuação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($this->data['listAtletas'])) {
                    $posicao = 1;
                    foreach ($this->data['listAtletas'] as $atleta) {
                        extract($atleta);
                        ?>
                        <tr>
                            <td><?= $posicao ?>º</td>
                            <td>
                                <strong><?= $name ?></strong><br>
                                <small><?= $apelido ?></small>
                            </td>
                            <td><?= $estilo_jogo ?></td>
                            <td><?= $mao_dominante ?></td>
                            <td><span class="badge-ranking"><?= $pontuacao_ranking ?> pts</span></td>
                            <td>
                                <a href="<?= URLADM ?>view-users/index/<?= $id ?>" class="btn-info">Ver</a>
                                <a href="<?= URLADM ?>edit-users/index/<?= $id ?>" class="btn-warning">Editar</a>
                            </td>
                        </tr>
                        <?php
                        $posicao++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='color: #f00;'>Nenhum atleta encontrado!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>