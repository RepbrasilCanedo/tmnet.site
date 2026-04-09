<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>

<style>
    /* ESTILOS APRIMORADOS PARA A TABELA DE LISTAGEM */
    .list-table { width: 100%; border-collapse: collapse; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-top: 15px; }
    .list-table thead th { background-color: #0044cc; color: #fff; padding: 15px; font-weight: bold; text-transform: uppercase; font-size: 13px; letter-spacing: 0.5px; }
    .list-table tbody td { padding: 14px 15px; border-bottom: 1px solid #eef2fa; color: #444; font-size: 14px; vertical-align: middle; }
    .list-table tbody tr:nth-child(even) { background-color: #f8f9fa; }
    .list-table tbody tr:nth-child(odd) { background-color: #ffffff; }
    .list-table tbody tr:hover { background-color: #e2e8f0; transition: background-color 0.2s ease; }
    .btn-action-edit { background-color: #ffc107; color: #212529; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: bold; transition: 0.3s; display: inline-block; }
    .btn-action-edit:hover { background-color: #e0a800; }

    /* DOCAN FIX: Estilos Responsivos para Mobile */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; margin-bottom: 15px; border-radius: 8px; }
    @media (max-width: 768px) {
        .list-table th, .list-table td { font-size: 12px; padding: 10px 8px; }
        .top-list { flex-direction: column; align-items: flex-start; gap: 10px; }
        .top-list-right { width: 100%; }
        .top-list-right .btn-success { display: block; text-align: center; width: 100%; }
    }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Categorias e Divisões</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>add-categoria/index" class="btn-success" style="background-color: #28a745; color: white;">+ Nova Categoria</a>
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

        <div class="table-responsive">
            <table class="list-table">
                <thead>
                    <tr>
                        <th style="text-align: left;">Nome da Categoria / Divisão</th>
                        <th style="text-align: center;">Faixa Etária</th>
                        <th style="text-align: center;">Nível Técnico (Rating)</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($this->data['listCat'])): ?>
                        <?php foreach ($this->data['listCat'] as $cat): ?>
                            <tr>
                                <td style="text-align: left;">
                                    <strong style="color: #0044cc; font-size: 15px;"><?= $cat['nome'] ?></strong>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        if(is_null($cat['idade_minima']) && is_null($cat['idade_maxima'])) {
                                            echo "<span style='color: #888; font-style: italic;'>Livre</span>";
                                        } elseif(is_null($cat['idade_minima'])) {
                                            echo "<span style='background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 12px;'>Até " . $cat['idade_maxima'] . " anos</span>";
                                        } elseif(is_null($cat['idade_maxima'])) {
                                            echo "<span style='background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 12px;'>" . $cat['idade_minima'] . "+ anos</span>";
                                        } else {
                                            echo "<span style='background: #e9ecef; padding: 3px 8px; border-radius: 12px; font-size: 12px;'>" . $cat['idade_minima'] . " a " . $cat['idade_maxima'] . " anos</span>";
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php 
                                        if(is_null($cat['pontuacao_minima']) && is_null($cat['pontuacao_maxima'])) {
                                            echo "<span style='color: #888; font-style: italic;'>Qualquer Rating</span>";
                                        } elseif(is_null($cat['pontuacao_minima'])) {
                                            echo "Até <strong>" . $cat['pontuacao_maxima'] . "</strong> pts";
                                        } elseif(is_null($cat['pontuacao_maxima'])) {
                                            echo "Acima de <strong>" . $cat['pontuacao_minima'] . "</strong> pts";
                                        } else {
                                            echo "<strong>" . $cat['pontuacao_minima'] . "</strong> a <strong>" . $cat['pontuacao_maxima'] . "</strong> pts";
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <a href="<?= URLADM ?>edit-categoria/index/<?= $cat['id'] ?>" class="btn-action-edit">✏️ Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px 20px; color: #666;">
                                Nenhuma categoria cadastrada para o seu clube.<br>
                                <small>Clique em "+ Nova Categoria" para começar a organizar os seus torneios.</small>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>