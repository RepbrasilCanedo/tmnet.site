<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<div class="dash-wrapper">
    <div class="row"><div class="top-list">
            <span class="title-content">Gestão de Torneios - TMNet</span>
            
            <div class="top-list-right" style="display: flex; align-items: center; gap: 15px;">
                <form method="POST" action="" style="display: flex; gap: 5px; margin: 0;">
                    <input type="text" name="search_nome" class="input-adm" placeholder="Pesquisar torneio..." 
                           value="<?= $this->data['form']['search_nome'] ?? '' ?>" 
                           style="margin: 0; padding: 6px 10px; width: 250px; border-radius: 4px; border: 1px solid #ccc;">
                    <button type="submit" class="btn-info" style="margin: 0; border: none; cursor: pointer;">Buscar</button>
                </form>
                
                <a href="<?= URLADM ?>add-competicoes/index" class="btn-success" style="margin: 0;">Nova Competição</a>
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

        <table class="list-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Torneio / Categoria</th>
                    <th>Local</th>
                    <th style="text-align: center;">Peso</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($this->data['listComp'])) {
                    foreach ($this->data['listComp'] as $comp) {
                        extract($comp);
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($data_evento)) ?></td>
                            <td>
                                <strong><?= $nome_torneio ?></strong><br>
                                <small style="color: #0044cc;"><?= $categoria_cbtm ?></small>
                            </td>
                            <td><?= $local_evento ?></td>
                            <td style="text-align: center;">
                                <span class="badge-ranking" style="background-color: #eee; color: #333; padding: 2px 8px; border-radius: 4px;">
                                    x<?= number_format($fator_multiplicador, 2) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= URLADM ?>view-competicao/index/<?= $id ?>" class="btn-info">Ver</a>
                                <a href="<?= URLADM ?>add-partidas/index/<?= $id ?>" class="btn-warning" style="background-color: #0044cc; color: white;">Gerir Jogos</a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='5' style='color: #f00; text-align: center;'>Nenhuma competição agendada para sua empresa!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>