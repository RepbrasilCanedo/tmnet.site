<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

function calcularIdadeListagem($dataNascimento) {
    if (empty($dataNascimento)) return '--';
    $dataNasc = new DateTime($dataNascimento);
    $hoje = new DateTime('today');
    return $dataNasc->diff($hoje)->y;
}
?>

<style>
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; margin-bottom: 15px; border-radius: 8px; }
    @media (max-width: 768px) {
        .list-table th, .list-table td { font-size: 12px; padding: 10px 5px; }
        .top-list { flex-direction: column; align-items: flex-start; gap: 10px; }
        .title-content { font-size: 18px; }
    }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content"> 
                <i class="fa-solid fa-users"></i> Atletas:  
                <?php 
                if (!empty($this->data['listAtletas'])) {
                    echo $this->data['listAtletas'][0]['nome_fantasia'];
                } else {
                    // DOCAN FIX: Fallback caso não haja atletas ainda para este clube
                    echo "Listagem Geral";
                }
                ?>
            </span> 
        </div>

        <div class="content-adm-alert">
            <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>

        <div class="table-responsive">
            <table class="list-table">
                <thead>
                    <tr>
                        <th>Posição</th>
                        <th>Nome / Apelido</th>
                        <th style="text-align: center;">Idade</th>
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
                            $idadeCalculada = calcularIdadeListagem($data_nascimento);
                            ?>
                            <tr>
                                <td><?= $posicao ?>º</td>
                                <td>
                                    <strong><?= $name ?></strong><br>
                                    <small><?= $apelido ?></small>
                                </td>
                                <td style="text-align: center;">
                                    <span style="background: #eef2fa; color: #0044cc; padding: 2px 6px; border-radius: 4px; font-weight: bold; border: 1px solid #cce5ff;">
                                        <?= $idadeCalculada ?>
                                    </span>
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
                        echo "<tr><td colspan='7' style='text-align:center; padding: 30px; color: #666;'>Nenhum atleta vinculado ao seu clube ou com inscrição aprovada.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>