<?php
if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

// Captura os nomes dos atletas para exibir na tela sem usar Select
$nomeAtletaA = "Atleta A";
$nomeAtletaB = "Atleta B";
if (!empty($this->data['atletas'])) {
    foreach ($this->data['atletas'] as $atleta) {
        if ($this->data['form']['atleta_a_id'] == $atleta['id']) $nomeAtletaA = $atleta['name'] . " (" . $atleta['apelido'] . ")";
        if ($this->data['form']['atleta_b_id'] == $atleta['id']) $nomeAtletaB = $atleta['name'] . " (" . $atleta['apelido'] . ")";
    }
}
?>
<style>
    .sumula-mobile-container { max-width: 800px; margin: 0 auto; background: #f8f9fa; padding: 15px; border-radius: 8px; }
    .versus-header { display: flex; flex-direction: column; gap: 15px; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
    
    .atleta-fixo { background: #f1f5f9; padding: 10px 15px; border-radius: 6px; font-size: 16px; font-weight: bold; color: #333; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid; }
    .atleta-a-box { border-color: #0044cc; }
    .atleta-b-box { border-color: #dc3545; }
    
    .btn-wo { background: #dc3545; color: white; border: none; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; cursor: pointer; text-transform: uppercase; }
    .vs-badge { text-align: center; font-size: 20px; font-weight: bold; color: #666; margin: 5px 0; }
    
    .saque-box { background: #eef2fa; padding: 10px; border-radius: 6px; text-align: center; margin-top: 10px; border: 1px dashed #0044cc; }
    .saque-title { font-size: 14px; font-weight: bold; color: #0044cc; margin-bottom: 8px; display: block; }
    .saque-options { display: flex; justify-content: center; gap: 20px; }
    .saque-options label { font-size: 16px; font-weight: bold; cursor: pointer; display: flex; align-items: center; gap: 5px; }
    .saque-options input[type="radio"] { transform: scale(1.3); cursor: pointer; }

    .set-card { background: #fff; border: 1px solid #dee2e6; border-radius: 8px; margin-bottom: 15px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); transition: opacity 0.3s; }
    .set-title { background: #0044cc; color: white; text-align: center; padding: 8px; font-weight: bold; font-size: 14px; letter-spacing: 1px; }
    
    .score-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; border-bottom: 1px solid #eee; }
    .score-row:last-child { border-bottom: none; }
    .player-label { font-size: 15px; font-weight: bold; color: #333; flex-grow: 1; }
    
    .stepper-control { display: flex; align-items: center; gap: 10px; }
    .btn-stepper { width: 40px; height: 40px; border-radius: 50%; border: none; font-size: 20px; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
    .btn-stepper:active { transform: scale(0.9); }
    .btn-minus { background-color: #e9ecef; color: #495057; }
    .btn-plus { background-color: #0044cc; color: white; }
    
    .input-score { width: 45px; text-align: center; font-size: 20px; font-weight: bold; border: 1px solid #ccc; border-radius: 6px; padding: 5px; -moz-appearance: textfield; }
    .input-score::-webkit-outer-spin-button, .input-score::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .cards-container { display: flex; gap: 15px; margin-top: 20px; flex-wrap: wrap; }
    .card-box { flex: 1; min-width: 250px; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6; }
    .card-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 5px; }
    .card-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    
    /* DOCAN FIX: Selo Live Score */
    .live-badge { font-size: 12px; font-weight: bold; padding: 4px 10px; border-radius: 20px; background: #e9ecef; color: #6c757d; border: 1px solid #dee2e6; display: flex; align-items: center; gap: 5px; }
</style>

<div class="dash-wrapper">
    <div class="row">
        <div class="top-list">
            <span class="title-content">📱 Súmula Eletrônica</span>
            <div class="top-list-right" style="display: flex; align-items: center; gap: 15px;">
                <div id="syncIndicator" class="live-badge">
                    <i class="fa-solid fa-satellite-dish"></i> Aguardando Início
                </div>
                <a href="<?= URLADM ?>view-competicao/index/<?= $this->data['form']['adms_competicao_id'] ?? '' ?>" class="btn-info">Voltar</a>
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

        <div class="content-adm sumula-mobile-container">
            <form method="POST" action="" class="form-adm" id="formPlacar">
                <input type="hidden" name="id" value="<?= $this->data['form']['id'] ?>">
                <input type="hidden" name="fase" value="<?= $this->data['form']['fase'] ?>">
                
                <input type="hidden" name="atleta_a_id" value="<?= $this->data['form']['atleta_a_id'] ?>">
                <input type="hidden" name="atleta_b_id" value="<?= $this->data['form']['atleta_b_id'] ?>">
                
                <input type="hidden" name="is_wo" id="is_wo" value="0">
                <input type="hidden" name="vencedor_wo_id" id="vencedor_wo_id" value="">

                <div class="versus-header">
                    <div class="atleta-fixo atleta-a-box">
                        <div>
                            <small style="color: #0044cc; display: block; font-size: 11px; text-transform: uppercase;">Lado A</small>
                            <?= $nomeAtletaA ?>
                        </div>
                        <button type="button" class="btn-wo" onclick="confirmarWO('<?= $this->data['form']['atleta_a_id'] ?>', 'Lado A')">Vitória W.O.</button>
                    </div>
                    
                    <div class="vs-badge">VS</div>
                    
                    <div class="atleta-fixo atleta-b-box">
                        <div>
                            <small style="color: #dc3545; display: block; font-size: 11px; text-transform: uppercase;">Lado B</small>
                            <?= $nomeAtletaB ?>
                        </div>
                        <button type="button" class="btn-wo" onclick="confirmarWO('<?= $this->data['form']['atleta_b_id'] ?>', 'Lado B')">Vitória W.O.</button>
                    </div>

                    <div class="saque-box">
                        <span class="saque-title">Sorteio Inicial: Quem começa sacando?</span>
                        <div class="saque-options">
                            <label style="color: #0044cc;">
                                <input type="radio" name="primeiro_saque" value="A" onchange="enviarLiveScore()" <?= (isset($this->data['form']['primeiro_saque']) && $this->data['form']['primeiro_saque'] == 'A') ? 'checked' : '' ?> required> Lado A
                            </label>
                            <label style="color: #dc3545;">
                                <input type="radio" name="primeiro_saque" value="B" onchange="enviarLiveScore()" <?= (isset($this->data['form']['primeiro_saque']) && $this->data['form']['primeiro_saque'] == 'B') ? 'checked' : '' ?> required> Lado B
                            </label>
                        </div>
                    </div>
                </div>

                <?php for($i = 1; $i <= 5; $i++): ?>
                <div class="set-card" id="card_set_<?= $i ?>">
                    <div class="set-title">SET <?= $i ?></div>
                    
                    <div class="score-row">
                        <div class="player-label" style="color: #0044cc;">Lado A</div>
                        <div class="stepper-control">
                            <button type="button" class="btn-stepper btn-minus" onclick="changeScore('pts_set<?= $i ?>_a', -1)">-</button>
                            <input type="number" name="pts_set<?= $i ?>_a" id="pts_set<?= $i ?>_a" class="input-score pts-input" onkeyup="gerenciarTravas()" value="<?= $this->data['form']["pts_set{$i}_a"] ?? '' ?>">
                            <button type="button" class="btn-stepper btn-plus" onclick="changeScore('pts_set<?= $i ?>_a', 1)">+</button>
                        </div>
                    </div>
                    
                    <div class="score-row">
                        <div class="player-label" style="color: #dc3545;">Lado B</div>
                        <div class="stepper-control">
                            <button type="button" class="btn-stepper btn-minus" onclick="changeScore('pts_set<?= $i ?>_b', -1)">-</button>
                            <input type="number" name="pts_set<?= $i ?>_b" id="pts_set<?= $i ?>_b" class="input-score pts-input" onkeyup="gerenciarTravas()" value="<?= $this->data['form']["pts_set{$i}_b"] ?? '' ?>">
                            <button type="button" class="btn-stepper btn-plus" style="background-color: #dc3545;" onclick="changeScore('pts_set<?= $i ?>_b', 1)">+</button>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>

                <small style="color: #666; display: block; text-align: center; margin-top: 10px;">*Sets seguintes só são desbloqueados ao concluir o set atual corretamente.</small>

                <div class="cards-container">
                    <div class="card-box">
                        <div class="card-title" style="color: #0044cc;">Cartões - Lado A</div>
                        <div class="card-item">
                            <span style="font-weight:bold; color:#d39e00;">🟨 Amarelo</span>
                            <div class="stepper-control">
                                <button type="button" class="btn-stepper btn-minus" style="width:30px;height:30px;" onclick="changeScore('cartao_amarelo_a', -1)">-</button>
                                <input type="number" name="cartao_amarelo_a" id="cartao_amarelo_a" class="input-score" style="width:40px; font-size:16px;" value="<?= $this->data['form']['cartao_amarelo_a'] ?? '0' ?>" max="2">
                                <button type="button" class="btn-stepper btn-plus" style="width:30px;height:30px; background:#ffc107; color:#000;" onclick="changeScore('cartao_amarelo_a', 1)">+</button>
                            </div>
                        </div>
                        <div class="card-item">
                            <span style="font-weight:bold; color:#dc3545;">🟥 Vermelho</span>
                            <div class="stepper-control">
                                <button type="button" class="btn-stepper btn-minus" style="width:30px;height:30px;" onclick="changeScore('cartao_vermelho_a', -1)">-</button>
                                <input type="number" name="cartao_vermelho_a" id="cartao_vermelho_a" class="input-score" style="width:40px; font-size:16px;" value="<?= $this->data['form']['cartao_vermelho_a'] ?? '0' ?>" max="1">
                                <button type="button" class="btn-stepper btn-plus" style="width:30px;height:30px; background:#dc3545;" onclick="changeScore('cartao_vermelho_a', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="card-box">
                        <div class="card-title" style="color: #dc3545;">Cartões - Lado B</div>
                        <div class="card-item">
                            <span style="font-weight:bold; color:#d39e00;">🟨 Amarelo</span>
                            <div class="stepper-control">
                                <button type="button" class="btn-stepper btn-minus" style="width:30px;height:30px;" onclick="changeScore('cartao_amarelo_b', -1)">-</button>
                                <input type="number" name="cartao_amarelo_b" id="cartao_amarelo_b" class="input-score" style="width:40px; font-size:16px;" value="<?= $this->data['form']['cartao_amarelo_b'] ?? '0' ?>" max="2">
                                <button type="button" class="btn-stepper btn-plus" style="width:30px;height:30px; background:#ffc107; color:#000;" onclick="changeScore('cartao_amarelo_b', 1)">+</button>
                            </div>
                        </div>
                        <div class="card-item">
                            <span style="font-weight:bold; color:#dc3545;">🟥 Vermelho</span>
                            <div class="stepper-control">
                                <button type="button" class="btn-stepper btn-minus" style="width:30px;height:30px;" onclick="changeScore('cartao_vermelho_b', -1)">-</button>
                                <input type="number" name="cartao_vermelho_b" id="cartao_vermelho_b" class="input-score" style="width:40px; font-size:16px;" value="<?= $this->data['form']['cartao_vermelho_b'] ?? '0' ?>" max="1">
                                <button type="button" class="btn-stepper btn-plus" style="width:30px;height:30px; background:#dc3545;" onclick="changeScore('cartao_vermelho_b', 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="button-area" style="margin-top: 30px;">
                    <button type="submit" name="AdmsEditPartida" id="btnSalvar" class="btn-success" value="Salvar" style="background-color: #28a745; color: white; width: 100%; height: 55px; font-size: 18px; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 6px rgba(40,167,69,0.3);">
                        ✅ ENCERRAR JOGO E SALVAR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// ============================================================================
// DOCAN ENGINE: Função que envia o placar em tempo real (AJAX)
// ============================================================================
function enviarLiveScore() {
    let form = document.getElementById('formPlacar');
    let formData = new FormData(form);
    formData.append('AjaxSyncLive', '1'); // Bandeira invisível para a Controller

    let indicator = document.getElementById('syncIndicator');
    if(indicator) {
        indicator.innerHTML = '<i class="fa-solid fa-rotate fa-spin"></i> Transmitindo...';
        indicator.style.color = '#856404';
        indicator.style.backgroundColor = '#fff3cd';
        indicator.style.borderColor = '#ffeeba';
    }

    fetch(window.location.href, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(indicator) {
            if(data.status) {
                indicator.innerHTML = '<i class="fa-solid fa-broadcast-tower"></i> AO VIVO';
                indicator.style.color = '#155724';
                indicator.style.backgroundColor = '#d4edda';
                indicator.style.borderColor = '#c3e6cb';
            } else {
                indicator.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Falha Sync';
                indicator.style.color = '#721c24';
                indicator.style.backgroundColor = '#f8d7da';
            }
        }
    })
    .catch(error => {
        console.error('Erro na Transmissão Live Score:', error);
        if(indicator) {
            indicator.innerHTML = '<i class="fa-solid fa-wifi" style="text-decoration: line-through;"></i> Offline';
            indicator.style.color = '#721c24';
            indicator.style.backgroundColor = '#f8d7da';
        }
    });
}

function changeScore(inputId, amount) {
    let input = document.getElementById(inputId);
    let currentValue = input.value === '' ? 0 : parseInt(input.value);
    let newValue = currentValue + amount;
    
    if (newValue < 0) newValue = 0;
    
    // Travas para os cartões
    if (inputId.includes('amarelo') && newValue > 2) newValue = 2;
    if (inputId.includes('vermelho') && newValue > 1) newValue = 1;

    if (input.value === '' && amount < 0) {
        input.value = '';
    } else {
        input.value = newValue;
    }
    
    if(inputId.includes('pts_set')) {
        gerenciarTravas();
    }

    // DOCAN FIX: Aciona a transmissão do Live Score sempre que a pontuação muda
    enviarLiveScore();
}

function validarSet(ptA, ptB) {
    if (ptA === '' || ptB === '') return false;
    let a = parseInt(ptA);
    let b = parseInt(ptB);
    
    if ((a === 11 && b <= 9) || (b === 11 && a <= 9)) return true;
    if (a >= 10 && b >= 10 && Math.abs(a - b) === 2) return true;
    return false;
}

function gerenciarTravas() {
    try {
        let setAnteriorValido = true; 
        let vitoriasA = 0;
        let vitoriasB = 0;

        for(let i = 1; i <= 5; i++) {
            let inputA = document.getElementById('pts_set' + i + '_a');
            let inputB = document.getElementById('pts_set' + i + '_b');
            let cardSet = document.getElementById('card_set_' + i);
            let btnMinusA = inputA.previousElementSibling;
            let btnPlusA = inputA.nextElementSibling;
            let btnMinusB = inputB.previousElementSibling;
            let btnPlusB = inputB.nextElementSibling;

            let jogoAcabou = (vitoriasA === 3 || vitoriasB === 3);

            if((i === 1 || setAnteriorValido) && !jogoAcabou) {
                inputA.readOnly = false; inputB.readOnly = false;
                btnMinusA.style.pointerEvents = 'auto'; btnPlusA.style.pointerEvents = 'auto';
                btnMinusB.style.pointerEvents = 'auto'; btnPlusB.style.pointerEvents = 'auto';
                cardSet.style.opacity = '1';
            } else {
                inputA.readOnly = true; inputB.readOnly = true;
                btnMinusA.style.pointerEvents = 'none'; btnPlusA.style.pointerEvents = 'none';
                btnMinusB.style.pointerEvents = 'none'; btnPlusB.style.pointerEvents = 'none';
                cardSet.style.opacity = '0.5';
            }

            setAnteriorValido = validarSet(inputA.value, inputB.value);
            if(setAnteriorValido) {
                if(parseInt(inputA.value) > parseInt(inputB.value)) vitoriasA++;
                else vitoriasB++;
            }
        }
    } catch(e) { console.error("Erro no gerenciarTravas: ", e); }
}

function confirmarWO(atletaId, lado) {
    if (confirm("Deseja declarar vitória por W.O. para o " + lado + "? O adversário será considerado ausente e o jogo encerrado instantaneamente.")) {
        
        let radiosSaque = document.getElementsByName('primeiro_saque');
        for(let i=0; i<radiosSaque.length; i++) {
            radiosSaque[i].required = false; 
        }

        document.getElementById('is_wo').value = '1';
        document.getElementById('vencedor_wo_id').value = atletaId;
        
        let btnSalvar = document.getElementById('btnSalvar');
        if(btnSalvar) btnSalvar.click(); 
    }
}

window.onload = gerenciarTravas;
</script>