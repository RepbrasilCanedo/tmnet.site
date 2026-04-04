<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
?>
<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Avançar Fase (Mata-Mata)</span>
            <div class="top-list-right">
                <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['competicao_id'] ?>" class="btn-info">Voltar à Súmula</a>
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

        <div class="content-adm" style="background: #f8f9fa; border-left: 4px solid #0044cc; padding: 25px;">
            <h3 style="margin-top: 0; color: #0044cc;">🚀 Evolução do Chaveamento</h3>
            <p style="font-size: 15px; line-height: 1.6;">Ao clicar no botão abaixo, o <strong>Tmnet</strong> irá analisar cada <b>Categoria</b> individualmente. O sistema identificará os vencedores das fases atuais e montará automaticamente os confrontos da fase seguinte.</p>
            
            <div style="background: #e9ecef; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <p style="margin: 0; color: #495057; font-size: 14px;">
                    <strong>Regra de Sincronia:</strong> Uma fase só avança em sua respectiva Categoria quando todos os jogos da rodada atual dela estiverem com o placar lançado (finalizados).
                </p>
            </div>
            
            <form method="POST" action="">
                <button type="submit" name="AdmsAvancarFase" class="btn-success" value="Avançar" style="background-color: #0044cc; font-size: 16px; height: 45px; padding: 0 30px; border-radius: 4px; border: none; color: white; cursor: pointer; font-weight: bold; box-shadow: 0 4px 6px rgba(0,68,204,0.2);">
                    Gerar Próxima Etapa do Torneio
                </button>
            </form>
            
            <p style="color: #666; font-size: 12px; margin-top: 15px;">*Os novos jogos serão criados sem mesa definida. Use a tela "Gerar Agenda" após avançar para chamar os atletas.</p>
        </div>
    </div>
</div>