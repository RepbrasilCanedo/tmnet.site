<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsViewCompeticao
{
    private array|null $result;

    function getResult(): array|null { return $this->result; }

    public function viewCompeticao(int $id): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        
        $view->fullRead("SELECT * FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", 
                        "id={$id}&empresa={$_SESSION['emp_user']}");
        $this->result['detalhes'] = $view->getResult()[0] ?? null;

        if ($this->result['detalhes']) {
            $view->fullRead(
                "SELECT p.*, ua.name as atleta_a, ub.name as atleta_b, uv.name as vencedor, c_cat.nome as cat_nome, c.tipo_genero
                 FROM adms_partidas p
                 INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
                 INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
                 INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
                 LEFT JOIN adms_users uv ON uv.id = p.vencedor_id
                 LEFT JOIN adms_categorias c_cat ON c_cat.id = p.adms_categoria_id
                 WHERE p.adms_competicao_id = :comp_id
                 ORDER BY c_cat.pontuacao_maxima DESC, c_cat.nome ASC, p.genero_partida ASC, p.id ASC", 
                 "comp_id={$id}"
            );
            $this->result['partidas'] = $view->getResult();

            $statusProgresso = [
                'has_grupos' => false,
                'has_matamata' => false,
                'is_finished' => false
            ];

            // =========================================================================
            // DOCAN FIX: NOVA LÓGICA BLINDADA DE ENCERRAMENTO DE TORNEIO
            // =========================================================================
            $jogosPendentes = 0;
            $categoriasNoTorneio = [];
            $categoriasComFinal = [];
            $sistemaDisputa = (int)($this->result['detalhes']['sistema_disputa'] ?? 1);

            if (!empty($this->result['partidas'])) {
                foreach ($this->result['partidas'] as $p) {
                    $catId = $p['adms_categoria_id'] ?? 0;
                    $categoriasNoTorneio[$catId] = true;

                    // Se algum jogo (qualquer um) não tem vencedor, soma pendência
                    if (empty($p['vencedor_id']) && $p['is_wo'] == 0) {
                        $jogosPendentes++;
                    }

                    if (stripos($p['fase'], 'Grupo') !== false || stripos($p['fase'], 'Classificat') !== false) {
                        $statusProgresso['has_grupos'] = true;
                    } else {
                        $statusProgresso['has_matamata'] = true;
                    }

                    // Regista se esta categoria específica já gerou a sua grande Final
                    if ($p['fase'] === 'Final') {
                        $categoriasComFinal[$catId] = true;
                    }
                }
            }

            $isFinished = false;
            
            // 1ª Trava: Não pode haver nenhum jogo no torneio à espera de resultado
            if (!empty($this->result['partidas']) && $jogosPendentes === 0) {
                
                if ($sistemaDisputa == 2) {
                    // Se for "Todos contra Todos" (Sem mata-mata), basta acabar os jogos
                    $isFinished = true;
                } else {
                    // 2ª Trava: Se for Mata-Mata, TODAS as categorias têm de ter a sua Final gerada e jogada!
                    if (count($categoriasNoTorneio) === count($categoriasComFinal)) {
                        $isFinished = true;
                    }
                }
            }

            $statusProgresso['is_finished'] = $isFinished;
            $this->result['status_progresso'] = $statusProgresso;

            // INTELIGÊNCIA DO PÓDIO
            $view->fullRead(
                "SELECT p.fase, p.vencedor_id, p.atleta_a_id, p.atleta_b_id, p.genero_partida,
                        ua.name as atleta_a_nome, ub.name as atleta_b_nome, uv.name as vencedor_nome,
                        c_cat.nome as cat_nome, c.tipo_genero
                 FROM adms_partidas p
                 INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
                 INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
                 INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
                 LEFT JOIN adms_users uv ON uv.id = p.vencedor_id
                 LEFT JOIN adms_categorias c_cat ON c_cat.id = p.adms_categoria_id
                 WHERE p.adms_competicao_id = :comp_id 
                   AND p.fase IN ('Final', 'Semifinal') 
                   AND p.vencedor_id IS NOT NULL AND p.vencedor_id > 0
                 ORDER BY c_cat.pontuacao_maxima DESC, c_cat.nome ASC, p.genero_partida ASC",
                 "comp_id={$id}"
            );
            
            $jogosFinais = $view->getResult();
            $podios = [];

            if ($jogosFinais) {
                foreach ($jogosFinais as $jogo) {
                    $catName = $jogo['cat_nome'] ?? 'Categoria Livre';
                    $tipoGenero = $jogo['tipo_genero'] ?? 1;
                    $genNome = 'Misto';
                    if ($tipoGenero == 2) {
                        $genNome = ($jogo['genero_partida'] == 'F') ? 'Feminino' : 'Masculino';
                    }
                    
                    $chavePodio = $catName . "_" . $genNome;

                    if (!isset($podios[$chavePodio])) {
                        $podios[$chavePodio] = [
                            'titulo' => ($genNome == 'Misto') ? $catName : $catName . " - " . $genNome,
                            'campeao' => null, 
                            'vice' => null, 
                            'terceiros' => []
                        ];
                    }

                    $perdedorNome = ($jogo['vencedor_id'] == $jogo['atleta_a_id']) ? $jogo['atleta_b_nome'] : $jogo['atleta_a_nome'];

                    if ($jogo['fase'] == 'Final') {
                        $podios[$chavePodio]['campeao'] = $jogo['vencedor_nome'];
                        $podios[$chavePodio]['vice'] = $perdedorNome;
                    } elseif ($jogo['fase'] == 'Semifinal') {
                        $podios[$chavePodio]['terceiros'][] = $perdedorNome;
                    }
                }
            }
            $this->result['podios'] = $podios;
        }
    }

    // =========================================================================
    // DOCAN FIX: MUDANÇA DE STATUS DA INSCRIÇÃO (1 ou 2)
    // =========================================================================
    public function mudarStatusInscricao(int $compId, int $novoStatus): void
    {
        $up = new \App\adms\Models\helper\AdmsUpdate();
        $up->exeUpdate("adms_competicoes", ['status_inscricao' => $novoStatus], "WHERE id=:id AND empresa_id=:emp", "id={$compId}&emp={$_SESSION['emp_user']}");
        
        if ($up->getResult()) {
            if ($novoStatus == 2) {
                $_SESSION['msg'] = "<p class='alert-success'>Inscrições Encerradas! A competição está Em Andamento.</p>";
            } else {
                $_SESSION['msg'] = "<p class='alert-info'>Inscrições Reabertas com sucesso.</p>";
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ao tentar alterar o status da competição.</p>";
        }
    }

    // =========================================================================
    // O MOTOR DE PROCESSAMENTO DO RANKING (CBTM) CONTINUA INTACTO AQUI
    // =========================================================================
    public function processarRankingOficial(int $compId): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        
        $view->fullRead("SELECT * FROM adms_competicoes WHERE id=:id LIMIT 1", "id={$compId}");
        $comp = $view->getResult()[0] ?? null;

        if (!$comp || $comp['ranking_processado'] == 1) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada ou Ranking já processado!</p>";
            return;
        }

        $pesoEvento = (float) $comp['fator_multiplicador'];
        $rankingAtletas = [];

        $addPontos = function(int $userId, int $pontos) use (&$rankingAtletas) {
            if (!isset($rankingAtletas[$userId])) $rankingAtletas[$userId] = 0;
            $rankingAtletas[$userId] += $pontos;
        };

        // ETAPA A: PONTOS DE PARTICIPAÇÃO
        $ptsParticipacao = (int) $comp['pts_participacao'];
        if ($ptsParticipacao > 0) {
            $view->fullRead("SELECT DISTINCT adms_user_id FROM adms_inscricoes WHERE adms_competicao_id=:id", "id={$compId}");
            if ($view->getResult()) {
                foreach ($view->getResult() as $inscrito) {
                    $addPontos($inscrito['adms_user_id'], $ptsParticipacao);
                }
            }
        }

        // ETAPA B: PÓDIOS
        $view->fullRead(
            "SELECT id, fase, vencedor_id, atleta_a_id, atleta_b_id 
             FROM adms_partidas 
             WHERE adms_competicao_id = :comp_id AND vencedor_id IS NOT NULL AND vencedor_id > 0", 
            "comp_id={$compId}"
        );
        $todasPartidas = $view->getResult() ?: [];

        foreach ($todasPartidas as $p) {
            $vencedor = $p['vencedor_id'];
            $perdedor = ($p['vencedor_id'] == $p['atleta_a_id']) ? $p['atleta_b_id'] : $p['atleta_a_id'];

            if ($p['fase'] === 'Final') {
                $addPontos($vencedor, (int)$comp['pts_campeao']); 
                $addPontos($perdedor, (int)$comp['pts_vice']);    
            } elseif ($p['fase'] === 'Semifinal') {
                $addPontos($perdedor, (int)$comp['pts_terceiro']);
            } elseif ($p['fase'] === 'Quartas de Final') {
                $addPontos($perdedor, (int)$comp['pts_quartas']); 
            }
        }

        // ETAPA C: RATING DINÂMICO CBTM
        $ptsVitoriaFixa = (int)$comp['pts_vitoria_jogo'];
        $ptsDerrotaFixa = (int)$comp['pts_derrota_jogo'];

        $usarMotorCbtm = ($ptsVitoriaFixa == 0 && $ptsDerrotaFixa == 0);

        foreach ($todasPartidas as $p) {
            if (isset($p['is_wo']) && $p['is_wo'] == 1) continue;

            $vencedor = $p['vencedor_id'];
            $perdedor = ($p['vencedor_id'] == $p['atleta_a_id']) ? $p['atleta_b_id'] : $p['atleta_a_id'];

            if (!$usarMotorCbtm) {
                $addPontos($vencedor, $ptsVitoriaFixa);
                $addPontos($perdedor, $ptsDerrotaFixa);
            } else {
                $view->fullRead("SELECT id, pontuacao_ranking FROM adms_users WHERE id IN ({$vencedor}, {$perdedor})");
                $ptsRank = [];
                foreach ($view->getResult() as $u) {
                    $ptsRank[$u['id']] = (int)$u['pontuacao_ranking'];
                }

                $ratingVencedor = $ptsRank[$vencedor] ?? 0;
                $ratingPerdedor = $ptsRank[$perdedor] ?? 0;
                
                $delta = abs($ratingVencedor - $ratingPerdedor);
                $isVitoriaEsperada = ($ratingVencedor >= $ratingPerdedor);

                $ptsAddVencedor = 0;
                $ptsAddPerdedor = 0; 

                if ($isVitoriaEsperada) {
                    if ($delta >= 750) { $ptsAddVencedor = 1; $ptsAddPerdedor = 0; }
                    elseif ($delta >= 500) { $ptsAddVencedor = 2; $ptsAddPerdedor = 0; }
                    elseif ($delta >= 400) { $ptsAddVencedor = 3; $ptsAddPerdedor = 1; }
                    elseif ($delta >= 300) { $ptsAddVencedor = 4; $ptsAddPerdedor = 2; }
                    elseif ($delta >= 200) { $ptsAddVencedor = 5; $ptsAddPerdedor = 3; }
                    elseif ($delta >= 150) { $ptsAddVencedor = 6; $ptsAddPerdedor = 4; }
                    elseif ($delta >= 100) { $ptsAddVencedor = 7; $ptsAddPerdedor = 5; }
                    elseif ($delta >= 50) { $ptsAddVencedor = 8; $ptsAddPerdedor = 6; }
                    elseif ($delta >= 25) { $ptsAddVencedor = 9; $ptsAddPerdedor = 7; }
                    else { $ptsAddVencedor = 10; $ptsAddPerdedor = 8; }
                } else {
                    if ($delta >= 500) { $ptsAddVencedor = 30; $ptsAddPerdedor = 22; }
                    elseif ($delta >= 400) { $ptsAddVencedor = 26; $ptsAddPerdedor = 20; }
                    elseif ($delta >= 300) { $ptsAddVencedor = 23; $ptsAddPerdedor = 18; }
                    elseif ($delta >= 200) { $ptsAddVencedor = 20; $ptsAddPerdedor = 16; }
                    elseif ($delta >= 150) { $ptsAddVencedor = 18; $ptsAddPerdedor = 14; }
                    elseif ($delta >= 100) { $ptsAddVencedor = 16; $ptsAddPerdedor = 12; }
                    elseif ($delta >= 50) { $ptsAddVencedor = 14; $ptsAddPerdedor = 11; }
                    elseif ($delta >= 25) { $ptsAddVencedor = 12; $ptsAddPerdedor = 10; }
                    else { $ptsAddVencedor = 11; $ptsAddPerdedor = 9; }
                }

                $ptsAddVencedor = (int) round($ptsAddVencedor * $pesoEvento);
                $ptsAddPerdedor = (int) round($ptsAddPerdedor * $pesoEvento);

                $addPontos($vencedor, $ptsAddVencedor);
                $addPontos($perdedor, $ptsAddPerdedor);
            }
        }

        if (!empty($rankingAtletas)) {
            $up = new \App\adms\Models\helper\AdmsUpdate();
            
            foreach ($rankingAtletas as $userId => $pontosGanhos) {
                if ($pontosGanhos > 0) {
                    $view->fullRead("SELECT pontuacao_ranking FROM adms_users WHERE id=:id LIMIT 1", "id={$userId}");
                    $pontosAtuais = $view->getResult()[0]['pontuacao_ranking'] ?? 0;
                    
                    $dataUpdate = [
                        'pontuacao_ranking' => $pontosAtuais + $pontosGanhos,
                        'modified' => date("Y-m-d H:i:s")
                    ];
                    $up->exeUpdate("adms_users", $dataUpdate, "WHERE id=:id", "id={$userId}");
                }
            }
        }

        $travaUpdate = new \App\adms\Models\helper\AdmsUpdate();
        $travaUpdate->exeUpdate("adms_competicoes", ['ranking_processado' => 1, 'status_inscricao' => 3], "WHERE id=:id", "id={$compId}");

        $_SESSION['msg'] = "<p class='alert-success'>⭐ RANKING PROCESSADO COM SUCESSO! A competição foi Encerrada.</p>";
    }
}