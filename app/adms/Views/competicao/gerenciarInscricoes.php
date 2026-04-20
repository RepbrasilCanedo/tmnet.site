<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

$categorias = $this->data['categorias_torneio'] ?? [];
$dataEvento = $this->data['data_evento'] ?? date('Y-m-d');

function calcIdade($nascimento, $dataEvt) {
    if (empty($nascimento) || $nascimento === '0000-00-00') return 0;
    $dtNasc = new DateTime($nascimento);
    $dtEvento = new DateTime($dataEvt);
    return $dtNasc->diff($dtEvento)->y;
}
?>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">Gestão de Inscritos e Chaves</span>
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

        <?php if (!empty($this->data['pendentes']) && $this->data['pendentes'] > 0): ?>
            <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; border-left: 5px solid #ffc107; margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i> <strong>Aviso Importante:</strong> Existem <b><?= $this->data['pendentes'] ?> atleta(s)</b> com inscrições <strong>Aguardando Pagamento</strong>.<br>
                <small>Eles sumiram do Select porque já usaram as suas vagas, mas não aparecem nas Chaves até serem aprovados. <a href="<?= URLADM ?>gerir-inscricoes/index?comp=<?= $this->data['competicao_id'] ?>" style="color: #0044cc; font-weight: bold; text-decoration: underline;">Vá ao menu "Gerir Inscrições" para os aprovar.</a></small>
            </div>
        <?php endif; ?>

        <?php if (empty($categorias)): ?>
            <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 6px; border-left: 5px solid #ffeeba; margin-bottom: 20px; font-weight: bold;">
                ⚠️ Atenção: Este torneio não possui nenhuma Categoria vinculada!<br>
                <small style="font-weight: normal;">Vá em "Editar Competição", marque as caixinhas das categorias que vão acontecer neste evento e guarde para poder inscrever atletas.</small>
            </div>
        <?php endif; ?>

        <div class="content-adm" style="background: #f8f9fa; border-left: 4px solid #0044cc; padding: 20px; margin-bottom: 20px;">
            <h3 style="margin-top: 0; color: #0044cc;">Adicionar Atleta Manualmente (Modo Admin)</h3>
            <p style="font-size: 13px; color: #555;">Regulamento: Máximo de 2 inscrições por atleta.</p>
            
            <form method="POST" action="" id="form-inscricao">
                
                <div style="margin-bottom: 15px;">
                    <label class="title-input">Selecione o Atleta:</label>
                    <select name="adms_user_id" id="atleta_select" class="input-adm" style="max-width: 600px;" onchange="filtrarCategorias()" required>
                        <option value="">-- Selecione o Atleta --</option>
                        <?php if (!empty($this->data['disponiveis'])): ?>
                            <?php foreach ($this->data['disponiveis'] as $atl): ?>
                                <?php 
                                    $idade = calcIdade($atl['data_nascimento'], $dataEvento); 
                                    $rating = (float)$atl['pontuacao_ranking'];
                                    $qtdIns = (int)$atl['qtd_inscricoes'];
                                    $catsInscritas = $atl['cats_inscritas'] ?? ''; 
                                ?>
                                <option value="<?= $atl['id'] ?>" data-idade="<?= $idade ?>" data-rating="<?= $rating ?>" data-inscritas="<?= $qtdIns ?>" data-cats-inscritas="<?= $catsInscritas ?>">
                                    <?= $atl['name'] ?> (<?= $atl['apelido'] ?>) | Pts: <?= $rating ?> | Vagas Usadas: <?= $qtdIns ?>/2
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div style="background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                    <strong style="color: #333; display: block; margin-bottom: 10px;">Categorias Disponíveis para o Atleta Selecionado:</strong>
                    <div id="categorias_checkboxes" style="display: flex; gap: 15px; flex-wrap: wrap;">
                        <span style="color: #888; font-size: 14px;">Selecione um atleta acima para visualizar as categorias permitidas.</span>
                    </div>
                </div>
                
                <button type="submit" name="AdmsAddAtleta" class="btn-success" value="Adicionar" style="background-color: #28a745; font-weight: bold; width: 100%; height: 45px;">
                    ➕ Inscrever nas Categorias Marcadas
                </button>
            </form>
        </div>

        <h3 style="color: #333;">Atletas APROVADOS nas Chaves (<?= !empty($this->data['inscritos']) ? count($this->data['inscritos']) : 0 ?> vagas)</h3>
        <table class="list-table">
            <thead>
                <tr>
                    <th style="background: #333; color: white;">Categoria / Divisão</th>
                    <th style="background: #333; color: white;">Atleta</th>
                    <th style="background: #333; color: white; text-align: center;">Rating Atual</th>
                    <th style="background: #333; color: white; text-align: center;">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($this->data['inscritos'])): ?>
                    <?php 
                    $catAtual = "";
                    foreach ($this->data['inscritos'] as $inscrito): 
                        if ($catAtual != $inscrito['nome_categoria']) {
                            echo "<tr><td colspan='4' style='background: #eef2fa; color: #0044cc; font-weight: bold; padding: 10px 15px;'>🏆 " . $inscrito['nome_categoria'] . "</td></tr>";
                            $catAtual = $inscrito['nome_categoria'];
                        }
                    ?>
                        <tr>
                            <td><small style="color: #888;"><?= $inscrito['nome_categoria'] ?></small></td>
                            <td><strong><?= $inscrito['name'] ?></strong> <span style="color: #666; font-size: 13px;">(<?= $inscrito['apelido'] ?>)</span></td>
                            <td style="text-align: center;">⭐ <?= $inscrito['pontuacao_ranking'] ?> pts</td>
                            <td style="text-align: center;">
                                <a href="<?= URLADM ?>gerenciar-inscricoes/index/<?= $this->data['competicao_id'] ?>?remover=<?= $inscrito['inscricao_id'] ?>" class="btn-danger" style="padding: 4px 8px; font-size: 12px; background: #dc3545; color: white; border-radius: 4px; text-decoration: none;" onclick="return confirm('Remover atleta DESTA categoria?');">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" style="text-align: center; padding: 30px;">Nenhum atleta APROVADO até o momento. Aprovações são feitas no menu "Gerir Inscrições".</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const categoriasOriginais = <?= json_encode($categorias ?: []) ?>;

function filtrarCategorias() {
    const selectAtleta = document.getElementById('atleta_select');
    const container = document.getElementById('categorias_checkboxes');
    
    const optAtleta = selectAtleta.options[selectAtleta.selectedIndex];
    
    if (!optAtleta.value) {
        container.innerHTML = '<span style="color: #888; font-size: 14px;">Selecione um atleta acima para visualizar as categorias permitidas.</span>';
        return;
    }

    if (categoriasOriginais.length === 0) {
        container.innerHTML = '<span style="color: #dc3545; font-weight: bold; font-size: 14px;">⚠️ O torneio não tem categorias.</span>';
        return;
    }

    const idadeAtleta = parseInt(optAtleta.getAttribute('data-idade')) || 0;
    const ratingAtleta = parseFloat(optAtleta.getAttribute('data-rating')) || 0;
    const inscritasJa = parseInt(optAtleta.getAttribute('data-inscritas')) || 0;
    
    const catsInscritasStr = optAtleta.getAttribute('data-cats-inscritas') || "";
    const arrayCatsJaInscritas = catsInscritasStr.split(',');
    
    const vagasDisponiveis = 2 - inscritasJa;

    container.innerHTML = ''; 
    let countDisponiveis = 0;

    categoriasOriginais.forEach(cat => {
        if (arrayCatsJaInscritas.includes(cat.id.toString())) { return; }

        let apto = true;
        
        let i_min = (cat.idade_minima !== null && cat.idade_minima !== "") ? parseInt(cat.idade_minima) : null;
        let i_max = (cat.idade_maxima !== null && cat.idade_maxima !== "") ? parseInt(cat.idade_maxima) : null;
        let r_max = (cat.pontuacao_maxima !== null && cat.pontuacao_maxima !== "") ? parseFloat(cat.pontuacao_maxima) : null;

        if (i_max !== null && idadeAtleta > i_max) apto = false;
        if (i_min !== null && idadeAtleta < i_min) apto = false;
        if (r_max !== null && ratingAtleta > r_max) apto = false;

        if (apto) {
            let label = document.createElement("label");
            label.style.cssText = "cursor: pointer; background: #eef2fa; padding: 8px 12px; border-radius: 4px; font-size: 14px; font-weight: bold; color: #0044cc; display: flex; align-items: center; gap: 8px; border: 1px solid #cce5ff;";
            
            let checkbox = document.createElement("input");
            checkbox.type = "checkbox";
            checkbox.name = "categorias_selecionadas[]";
            checkbox.value = cat.id;
            checkbox.style.cssText = "width: 16px; height: 16px; cursor: pointer;";
            
            checkbox.onclick = function() {
                let marcados = container.querySelectorAll('input[type="checkbox"]:checked').length;
                if (marcados > vagasDisponiveis) {
                    this.checked = false; 
                    alert('Regulamento: O limite máximo é de 2 categorias por atleta! O atleta já tem ' + inscritasJa + ' inscrição(ões) ativa(s).');
                }
            };
            
            let spanNode = document.createElement("span");
            spanNode.innerText = cat.nome;

            label.appendChild(checkbox);
            label.appendChild(spanNode);
            container.appendChild(label);
            
            countDisponiveis++;
        }
    });

    if (countDisponiveis === 0) {
        if (inscritasJa >= 2) {
            container.innerHTML = '<span style="color: #28a745; font-weight: bold; font-size: 14px;">✅ Este atleta já utilizou as suas 2 vagas neste torneio!</span>';
        } else {
            container.innerHTML = '<span style="color: #dc3545; font-weight: bold; font-size: 14px;">🚫 O Atleta não tem Idade ou Nível Técnico para as categorias restantes.</span>';
        }
    }
}
</script>