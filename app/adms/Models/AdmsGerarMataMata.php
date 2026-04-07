<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsGerarMataMata
{
    private bool $result = false;
    private array $classificacao = [];

    function getResult(): bool { return $this->result; }

    public function getClassificacaoGrupos(int $compId): array
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // DOCAN FIX: Removido o bloqueio "AND i.grupo != 'Único'" para aceitar Todos contra Todos
        $read->fullRead(
            "SELECT i.adms_user_id, i.grupo, i.adms_categoria_id, u.name, u.genero, cat.nome as cat_nome, c.tipo_genero
             FROM adms_inscricoes i 
             INNER JOIN adms_users u ON u.id = i.adms_user_id 
             INNER JOIN adms_competicoes c ON c.id = i.adms_competicao_id
             LEFT JOIN adms_categorias cat ON cat.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id AND i.grupo IS NOT NULL
             ORDER BY cat.pontuacao_maxima DESC, cat.nome ASC, i.grupo ASC", 
            "comp_id={$compId}"
        );
        $inscritos = $read->getResult();

        $read->fullRead(
            "SELECT vencedor_id FROM adms_partidas 
             WHERE adms_competicao_id = :comp_id AND fase LIKE 'Grupo%' AND vencedor_id IS NOT NULL AND vencedor_id > 0", 
            "comp_id={$compId}"
        );
        $partidas = $read->getResult() ?: [];

        $vitorias = [];
        foreach ($partidas as $p) {
            $vid = $p['vencedor_id'];
            $vitorias[$vid] = isset($vitorias[$vid]) ? $vitorias[$vid] + 1 : 1;
        }

        if ($inscritos) {
            foreach ($inscritos as $ins) {
                $uid = $ins['adms_user_id'];
                $catId = $ins['adms_categoria_id'] ?? 0;
                $nomeCategoria = $ins['cat_nome'] ?? 'Categoria Livre';
                $tipoGenero = $ins['tipo_genero'] ?? 1;
                
                $genId = ($tipoGenero == 2) ? $ins['genero'] : 'X';
                $genNome = 'Misto';
                if ($tipoGenero == 2) {
                    $genNome = ($genId == 'F') ? 'Feminino' : 'Masculino';
                }

                if (!isset($this->classificacao[$catId][$genId])) {
                    $this->classificacao[$catId][$genId] = [
                        'nome_categoria' => $nomeCategoria, 
                        'nome_genero' => $genNome, 
                        'grupos' => []
                    ];
                }

                $grupoLimpo = str_replace(['M-', 'F-'], '', $ins['grupo']);

                $this->classificacao[$catId][$genId]['grupos'][$grupoLimpo][] = [
                    'id' => $uid,
                    'nome' => $ins['name'],
                    'vitorias' => $vitorias[$uid] ?? 0
                ];
            }

            foreach ($this->classificacao as $catId => $generos) {
                foreach ($generos as $genId => $genData) {
                    foreach ($genData['grupos'] as $grupo => $atletas) {
                        usort($this->classificacao[$catId][$genId]['grupos'][$grupo], function($a, $b) {
                            return $b['vitorias'] <=> $a['vitorias'];
                        });
                    }
                }
            }
        }

        return $this->classificacao;
    }

    public function gerarChaves(int $compId): void
    {
        $readCheck = new \App\adms\Models\helper\AdmsRead();
        $readCheck->fullRead(
            "SELECT id FROM adms_partidas 
             WHERE adms_competicao_id = :comp_id AND fase LIKE 'Grupo%' AND (vencedor_id IS NULL OR vencedor_id = 0) LIMIT 1",
            "comp_id={$compId}"
        );
        
        if ($readCheck->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não é possível gerar o Mata-Mata! Conclua primeiro todos os jogos da fase de grupos.</p>";
            $this->result = false;
            return;
        }

        $this->getClassificacaoGrupos($compId);

        if (empty($this->classificacao)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não há dados de grupos classificados!</p>";
            $this->result = false;
            return;
        }

        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete(
            "adms_partidas", 
            "WHERE adms_competicao_id = :comp_id AND (fase NOT LIKE 'Grupo%' AND fase != 'Classificatória')", 
            "comp_id={$compId}"
        );

        $create = new \App\adms\Models\helper\AdmsCreate();
        $jogosInseridos = 0;

        foreach ($this->classificacao as $catId => $generos) {
            foreach ($generos as $genId => $genData) {
                
                // CONTA QUANTOS GRUPOS EXISTEM NESTA CATEGORIA
                $numGrupos = count($genData['grupos']);

                if ($numGrupos == 1) {
                    // =========================================================
                    // LÓGICA 1: TODOS CONTRA TODOS (Apenas 1 Grupo Único)
                    // =========================================================
                    $grupoNome = array_key_first($genData['grupos']);
                    $atletas = $genData['grupos'][$grupoNome];
                    $qtdAtletas = count($atletas);

                    if ($qtdAtletas < 2) continue;

                    $cruzamentos = [];
                    $nomeFase = "Final";

                    // Define Cruzamentos Olímpicos dependendo do número de inscritos
                    if ($qtdAtletas >= 8) {
                        $nomeFase = "Quartas de Final";
                        $cruzamentos = [[0,7], [3,4], [2,5], [1,6]]; 
                    } elseif ($qtdAtletas >= 4) {
                        $nomeFase = "Semifinal";
                        $cruzamentos = [[0,3], [1,2]];
                    } elseif ($qtdAtletas >= 2) {
                        $nomeFase = "Final";
                        $cruzamentos = [[0,1]];
                    }

                    foreach ($cruzamentos as $par) {
                        $dadosPartida = [
                            'adms_competicao_id' => $compId,
                            'adms_categoria_id' => ($catId > 0) ? $catId : null,
                            'genero_partida' => $genId, 
                            'atleta_a_id' => $atletas[$par[0]]['id'],
                            'atleta_b_id' => $atletas[$par[1]]['id'],
                            'fase' => $nomeFase,
                            'mesa' => null, 
                            'horario_previsto' => null,
                            'status_partida' => 'Agendado',
                            'created' => date("Y-m-d H:i:s")
                        ];
                        $create->exeCreate("adms_partidas", $dadosPartida);
                        $jogosInseridos++;
                    }

                } else {
                    // =========================================================
                    // LÓGICA 2: MÚLTIPLOS GRUPOS (Padrão: Passam 2 por grupo)
                    // =========================================================
                    $primeiros = [];
                    $segundos = [];

                    foreach ($genData['grupos'] as $grupo => $atletas) {
                        if (isset($atletas[0])) $primeiros[] = $atletas[0];
                        if (isset($atletas[1])) $segundos[] = $atletas[1];
                    }

                    $qtdClassificados = count($primeiros) + count($segundos);
                    if ($qtdClassificados < 2) continue;

                    $nomeFase = "Final";
                    if ($qtdClassificados > 2 && $qtdClassificados <= 4) $nomeFase = "Semifinal";
                    elseif ($qtdClassificados > 4 && $qtdClassificados <= 8) $nomeFase = "Quartas de Final";
                    elseif ($qtdClassificados > 8 && $qtdClassificados <= 16) $nomeFase = "Oitavas de Final";

                    $totalGrupos = count($primeiros);
                    for ($i = 0; $i < $totalGrupos; $i++) {
                        $idAtletaA = $primeiros[$i]['id'];
                        
                        $indiceOponente = ($i % 2 == 0) ? $i + 1 : $i - 1;
                        if (!isset($segundos[$indiceOponente])) {
                            $indiceOponente = $i; 
                        }
                        $idAtletaB = $segundos[$indiceOponente]['id'];

                        $dadosPartida = [
                            'adms_competicao_id' => $compId,
                            'adms_categoria_id' => ($catId > 0) ? $catId : null,
                            'genero_partida' => $genId, 
                            'atleta_a_id' => $idAtletaA,
                            'atleta_b_id' => $idAtletaB,
                            'fase' => $nomeFase,
                            'mesa' => null, 
                            'horario_previsto' => null,
                            'status_partida' => 'Agendado',
                            'created' => date("Y-m-d H:i:s")
                        ];
                        $create->exeCreate("adms_partidas", $dadosPartida);
                        $jogosInseridos++;
                    }
                }
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Sucesso! {$jogosInseridos} jogos eliminatórios foram criados! <b>Vá ao menu 'Gerar Agenda' para distribuir as mesas.</b></p>";
        $this->result = true;
    }
}