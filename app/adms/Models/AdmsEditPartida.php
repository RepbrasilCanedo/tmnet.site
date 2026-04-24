<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditPartida
{
    private bool $result = false;
    private array|null $resultBd;
    private int $competicaoId;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }
    function getCompeticaoId(): int { return $this->competicaoId; }

    public function getPartida(int $id): void
    {
        $viewPartida = new \App\adms\Models\helper\AdmsRead();
        
        // DOCAN FIX: Traz os nomes dos atletas diretamente do banco (JOIN)
        $viewPartida->fullRead(
            "SELECT p.*, c.fator_multiplicador,
                    ua.name as nome_a, ua.apelido as apelido_a,
                    ub.name as nome_b, ub.apelido as apelido_b
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             LEFT JOIN adms_users ua ON ua.id = p.atleta_a_id
             LEFT JOIN adms_users ub ON ub.id = p.atleta_b_id
             WHERE p.id = :id AND c.empresa_id = :empresa LIMIT 1",
            "id={$id}&empresa={$_SESSION['emp_user']}"
        );

        $this->resultBd = $viewPartida->getResult();
    }

    // ========================================================================
    // DOCAN ENGINE: LIVE SCORE SINC (Blindado contra Undefined Keys)
    // ========================================================================
    public function syncLiveScore(array $data): void
    {
        $id = (int)$data['id'];
        
        // Calcula os sets provisórios em tempo real para a TV
        $setsA = 0;
        $setsB = 0;
        for ($i = 1; $i <= 5; $i++) {
            $ptA = (isset($data["pts_set{$i}_a"]) && $data["pts_set{$i}_a"] !== '') ? (int)$data["pts_set{$i}_a"] : null;
            $ptB = (isset($data["pts_set{$i}_b"]) && $data["pts_set{$i}_b"] !== '') ? (int)$data["pts_set{$i}_b"] : null;
            
            if ($ptA !== null && $ptB !== null) {
                if (($ptA === 11 && $ptB <= 9) || ($ptB === 11 && $ptA <= 9) || ($ptA >= 10 && $ptB >= 10 && abs($ptA - $ptB) >= 2)) {
                    if ($ptA > $ptB) $setsA++; else $setsB++;
                }
            }
        }
        
        $dataUpdate = [
            'pts_set1_a' => (isset($data['pts_set1_a']) && $data['pts_set1_a'] !== '') ? (int)$data['pts_set1_a'] : null,
            'pts_set1_b' => (isset($data['pts_set1_b']) && $data['pts_set1_b'] !== '') ? (int)$data['pts_set1_b'] : null,
            'pts_set2_a' => (isset($data['pts_set2_a']) && $data['pts_set2_a'] !== '') ? (int)$data['pts_set2_a'] : null,
            'pts_set2_b' => (isset($data['pts_set2_b']) && $data['pts_set2_b'] !== '') ? (int)$data['pts_set2_b'] : null,
            'pts_set3_a' => (isset($data['pts_set3_a']) && $data['pts_set3_a'] !== '') ? (int)$data['pts_set3_a'] : null,
            'pts_set3_b' => (isset($data['pts_set3_b']) && $data['pts_set3_b'] !== '') ? (int)$data['pts_set3_b'] : null,
            'pts_set4_a' => (isset($data['pts_set4_a']) && $data['pts_set4_a'] !== '') ? (int)$data['pts_set4_a'] : null,
            'pts_set4_b' => (isset($data['pts_set4_b']) && $data['pts_set4_b'] !== '') ? (int)$data['pts_set4_b'] : null,
            'pts_set5_a' => (isset($data['pts_set5_a']) && $data['pts_set5_a'] !== '') ? (int)$data['pts_set5_a'] : null,
            'pts_set5_b' => (isset($data['pts_set5_b']) && $data['pts_set5_b'] !== '') ? (int)$data['pts_set5_b'] : null,
            'sets_atleta_a' => $setsA, 
            'sets_atleta_b' => $setsB,
            'cartao_amarelo_a' => isset($data['cartao_amarelo_a']) ? (int)$data['cartao_amarelo_a'] : 0,
            'cartao_vermelho_a' => isset($data['cartao_vermelho_a']) ? (int)$data['cartao_vermelho_a'] : 0,
            'cartao_amarelo_b' => isset($data['cartao_amarelo_b']) ? (int)$data['cartao_amarelo_b'] : 0,
            'cartao_vermelho_b' => isset($data['cartao_vermelho_b']) ? (int)$data['cartao_vermelho_b'] : 0,
            'status_partida' => 'Em Andamento'
        ];

        if (isset($data['primeiro_saque'])) {
            $dataUpdate['primeiro_saque'] = $data['primeiro_saque'];
        }

        $upPartida = new \App\adms\Models\helper\AdmsUpdate();
        $upPartida->exeUpdate("adms_partidas", $dataUpdate, "WHERE id = :id", "id={$id}");

        $this->result = true; 
    }

    public function update(array $data): void
    {
        $this->getPartida($data['id']);
        
        if ($this->resultBd) {
            $dadosOriginais = $this->resultBd[0];
            $this->competicaoId = $dadosOriginais['adms_competicao_id'];
            
            if (!empty($dadosOriginais['vencedor_id'])) {
                $pontosEstorno = (int)$dadosOriginais['pontos_ganhos'];
                $this->ajustarRanking($dadosOriginais['vencedor_id'], -$pontosEstorno);
            }

            $setsA = 0;
            $setsB = 0;
            $isWO = isset($data['is_wo']) && $data['is_wo'] == '1';
            $novoVencedorId = 0;

            if ($isWO) {
                $novoVencedorId = (int)$data['vencedor_wo_id'];
                $setsA = ($novoVencedorId == $data['atleta_a_id']) ? 3 : 0;
                $setsB = ($novoVencedorId == $data['atleta_b_id']) ? 3 : 0;
            } else {
                $setAnteriorValido = true;
                for ($i = 1; $i <= 5; $i++) {
                    $ptA = (isset($data["pts_set{$i}_a"]) && $data["pts_set{$i}_a"] !== '') ? (int)$data["pts_set{$i}_a"] : null;
                    $ptB = (isset($data["pts_set{$i}_b"]) && $data["pts_set{$i}_b"] !== '') ? (int)$data["pts_set{$i}_b"] : null;
                    
                    if ($ptA !== null || $ptB !== null) {
                        
                        if (!$setAnteriorValido) {
                            $_SESSION['msg'] = "<p class='alert-danger'>Erro: O <b>Set {$i}</b> não pode ser preenchido sem concluir o anterior.</p>";
                            $this->result = false; return;
                        }

                        if ($ptA === null || $ptB === null) {
                            $_SESSION['msg'] = "<p class='alert-danger'>Erro no <b>Set {$i}</b>: Placar incompleto.</p>";
                            $this->result = false; return;
                        }

                        $setValido = false;
                        if (($ptA === 11 && $ptB <= 9) || ($ptB === 11 && $ptA <= 9)) {
                            $setValido = true;
                        } elseif ($ptA >= 10 && $ptB >= 10 && abs($ptA - $ptB) >= 2) {
                            $setValido = true;
                        }

                        if ($setValido) {
                            if ($ptA > $ptB) $setsA++; else $setsB++;
                            $setAnteriorValido = true;
                        } else {
                            $_SESSION['msg'] = "<p class='alert-danger'>Erro no <b>Set {$i}</b>: Placar inválido (Feche em 11 ou com 2 de vantagem acima de 10x10).</p>";
                            $this->result = false; return;
                        }
                    } else {
                        $setAnteriorValido = false; 
                    }
                }

                if ($setsA === 3 || $setsB === 3) {
                    $novoVencedorId = ($setsA === 3) ? $data['atleta_a_id'] : $data['atleta_b_id'];
                } else {
                    $_SESSION['msg'] = "<p class='alert-warning'>Aviso: É necessário vencer exatamente <b>3 sets</b> para encerrar a partida.</p>";
                    $this->result = false; return;
                }
            }

            $fator = (float)$dadosOriginais['fator_multiplicador'];
            $novosPontos = 10 * $fator;
            $this->ajustarRanking($novoVencedorId, $novosPontos);

            $dataUpdate = [
                'atleta_a_id' => $data['atleta_a_id'],
                'atleta_b_id' => $data['atleta_b_id'],
                'primeiro_saque' => $isWO ? null : ($data['primeiro_saque'] ?? null),
                'is_wo' => $isWO ? 1 : 0,
                'sets_atleta_a' => $setsA, 
                'sets_atleta_b' => $setsB, 
                'fase' => $data['fase'],
                'vencedor_id' => $novoVencedorId,
                'pontos_ganhos' => $novosPontos,
                'status_partida' => 'Finalizado',
                
                'pts_set1_a' => $isWO ? ($setsA == 3 ? 11 : 0) : ((isset($data['pts_set1_a']) && $data['pts_set1_a'] !== '') ? (int)$data['pts_set1_a'] : null),
                'pts_set1_b' => $isWO ? ($setsB == 3 ? 11 : 0) : ((isset($data['pts_set1_b']) && $data['pts_set1_b'] !== '') ? (int)$data['pts_set1_b'] : null),
                'pts_set2_a' => $isWO ? ($setsA == 3 ? 11 : 0) : ((isset($data['pts_set2_a']) && $data['pts_set2_a'] !== '') ? (int)$data['pts_set2_a'] : null),
                'pts_set2_b' => $isWO ? ($setsB == 3 ? 11 : 0) : ((isset($data['pts_set2_b']) && $data['pts_set2_b'] !== '') ? (int)$data['pts_set2_b'] : null),
                'pts_set3_a' => $isWO ? ($setsA == 3 ? 11 : 0) : ((isset($data['pts_set3_a']) && $data['pts_set3_a'] !== '') ? (int)$data['pts_set3_a'] : null),
                'pts_set3_b' => $isWO ? ($setsB == 3 ? 11 : 0) : ((isset($data['pts_set3_b']) && $data['pts_set3_b'] !== '') ? (int)$data['pts_set3_b'] : null),
                'pts_set4_a' => $isWO ? null : ((isset($data['pts_set4_a']) && $data['pts_set4_a'] !== '') ? (int)$data['pts_set4_a'] : null),
                'pts_set4_b' => $isWO ? null : ((isset($data['pts_set4_b']) && $data['pts_set4_b'] !== '') ? (int)$data['pts_set4_b'] : null),
                'pts_set5_a' => $isWO ? null : ((isset($data['pts_set5_a']) && $data['pts_set5_a'] !== '') ? (int)$data['pts_set5_a'] : null),
                'pts_set5_b' => $isWO ? null : ((isset($data['pts_set5_b']) && $data['pts_set5_b'] !== '') ? (int)$data['pts_set5_b'] : null),
                
                'cartao_amarelo_a' => isset($data['cartao_amarelo_a']) ? (int)$data['cartao_amarelo_a'] : 0,
                'cartao_vermelho_a' => isset($data['cartao_vermelho_a']) ? (int)$data['cartao_vermelho_a'] : 0,
                'cartao_amarelo_b' => isset($data['cartao_amarelo_b']) ? (int)$data['cartao_amarelo_b'] : 0,
                'cartao_vermelho_b' => isset($data['cartao_vermelho_b']) ? (int)$data['cartao_vermelho_b'] : 0
            ];

            $upPartida = new \App\adms\Models\helper\AdmsUpdate();
            $upPartida->exeUpdate("adms_partidas", $dataUpdate, "WHERE id = :id", "id={$data['id']}");

            if ($upPartida->getResult()) {
                $_SESSION['msg'] = $isWO ? "<p class='alert-success'>Partida encerrada por W.O.! Placar automático: 3x0.</p>" : "<p class='alert-success'>Súmula salva! Placar final: {$setsA} x {$setsB}.</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro ao salvar a súmula.</p>";
                $this->result = false;
            }
        }
    }

    // ========================================================================
    // DOCAN FIX: FUNÇÕES REPOSTAS COM SUCESSO!
    // ========================================================================
    private function ajustarRanking(int $atletaId, float|int $pontos): void
    {
        $readAtleta = new \App\adms\Models\helper\AdmsRead();
        $readAtleta->fullRead("SELECT pontuacao_ranking FROM adms_users WHERE id=:id LIMIT 1", "id={$atletaId}");
        
        if ($readAtleta->getResult()) {
            $pontuacaoAtual = $readAtleta->getResult()[0]['pontuacao_ranking'];
            $novaPontuacao = $pontuacaoAtual + $pontos;
            if ($novaPontuacao < 0) { $novaPontuacao = 0; }

            $dataUpdate['pontuacao_ranking'] = $novaPontuacao;
            $dataUpdate['modified'] = date("Y-m-d H:i:s");

            $upRanking = new \App\adms\Models\helper\AdmsUpdate();
            $upRanking->exeUpdate("adms_users", $dataUpdate, "WHERE id=:id", "id={$atletaId}");
        }
    }

    public function listAtletas(): array|null
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id, name, apelido FROM adms_users WHERE empresa_id = :empresa AND adms_access_level_id = 14 ORDER BY name ASC", "empresa={$_SESSION['emp_user']}");
        return $list->getResult();
    }
}