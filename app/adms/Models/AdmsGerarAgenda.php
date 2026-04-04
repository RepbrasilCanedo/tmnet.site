<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsGerarAgenda
{
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function gerarJogos(array $dados): void
    {
        $compId = (int)$dados['adms_competicao_id'];
        $qtdMesas = (int)$dados['qtd_mesas'];

        if ($qtdMesas < 1) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Informe pelo menos 1 mesa disponível!</p>";
            return;
        }

        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT horario_inicio FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $horarioInicioStr = $read->getResult()[0]['horario_inicio'] ?? '08:00:00';
        $horaInicioBase = strtotime("1970-01-01 " . $horarioInicioStr);

        $read->fullRead(
            "SELECT MAX(horario_previsto) as ultimo_horario 
             FROM adms_partidas 
             WHERE adms_competicao_id = :comp_id AND vencedor_id > 0", 
            "comp_id={$compId}"
        );
        $ultimoHorarioFinalizado = $read->getResult()[0]['ultimo_horario'] ?? null;
        if ($ultimoHorarioFinalizado) {
            $horaFinalizado = strtotime("1970-01-01 " . $ultimoHorarioFinalizado) + 1800;
            $horaInicioBase = max($horaInicioBase, $horaFinalizado);
        }

        $dispMesas = [];
        for ($i = 1; $i <= $qtdMesas; $i++) {
            $dispMesas[$i] = $horaInicioBase; 
        }
        $dispAtletas = []; 

        $read->fullRead(
            "SELECT id, atleta_a_id, atleta_b_id, fase FROM adms_partidas 
             WHERE adms_competicao_id = :comp_id 
             AND (vencedor_id IS NULL OR vencedor_id = 0) 
             ORDER BY id ASC", 
            "comp_id={$compId}"
        );
        
        $jogosPendentes = $read->getResult();

        if (!$jogosPendentes) {
            $_SESSION['msg'] = "<p class='alert-warning'>Aviso: Não há jogos em andamento para agendar nesta competição.</p>";
            return;
        }

        $jogosGrupos = [];
        $jogosMataMata = [];
        foreach ($jogosPendentes as $jogo) {
            $isMataMata = stripos($jogo['fase'], 'grupo') === false && stripos($jogo['fase'], 'classificat') === false;
            if ($isMataMata) {
                $jogosMataMata[] = $jogo;
            } else {
                $jogosGrupos[] = $jogo;
            }
        }

        $jogosAgendados = [];

        while (count($jogosGrupos) > 0) {
            $melhorMesa = 1;
            $horaLivreMesa = PHP_INT_MAX;
            foreach ($dispMesas as $mesaId => $hora) {
                if ($hora < $horaLivreMesa) {
                    $horaLivreMesa = $hora;
                    $melhorMesa = $mesaId;
                }
            }

            $melhorJogoIndex = -1;
            $menorAtraso = PHP_INT_MAX;
            $melhorHoraInicioJogo = PHP_INT_MAX;

            foreach ($jogosGrupos as $index => $jogo) {
                $idA = $jogo['atleta_a_id'];
                $idB = $jogo['atleta_b_id'];

                $horaLivreA = $dispAtletas[$idA] ?? $horaInicioBase;
                $horaLivreB = $dispAtletas[$idB] ?? $horaInicioBase;

                $horaLivreAmbos = max($horaLivreA, $horaLivreB);
                $horaInicioReal = max($horaLivreMesa, $horaLivreAmbos);
                $atraso = $horaInicioReal - $horaLivreMesa;

                if ($atraso < $menorAtraso) {
                    $menorAtraso = $atraso;
                    $melhorJogoIndex = $index;
                    $melhorHoraInicioJogo = $horaInicioReal;
                }
                if ($atraso == 0) break; 
            }

            $jogoEscolhido = $jogosGrupos[$melhorJogoIndex];
            $jogosAgendados[] = [
                'id' => $jogoEscolhido['id'],
                'mesa' => $melhorMesa,
                'horario_previsto' => date('H:i:s', $melhorHoraInicioJogo)
            ];

            $horaFimJogo = $melhorHoraInicioJogo + 1800;
            $dispMesas[$melhorMesa] = $horaFimJogo;
            if ($jogoEscolhido['atleta_a_id']) $dispAtletas[$jogoEscolhido['atleta_a_id']] = $horaFimJogo;
            if ($jogoEscolhido['atleta_b_id']) $dispAtletas[$jogoEscolhido['atleta_b_id']] = $horaFimJogo;

            unset($jogosGrupos[$melhorJogoIndex]);
            $jogosGrupos = array_values($jogosGrupos);
        }

        if (count($jogosMataMata) > 0) {
            $horaMaisTardeDeTodasAsMesas = max($dispMesas);
            if ($horaMaisTardeDeTodasAsMesas < $horaInicioBase) $horaMaisTardeDeTodasAsMesas = $horaInicioBase;

            for ($i = 1; $i <= $qtdMesas; $i++) {
                $dispMesas[$i] = $horaMaisTardeDeTodasAsMesas;
            }

            while (count($jogosMataMata) > 0) {
                $melhorMesa = 1;
                $horaLivreMesa = PHP_INT_MAX;
                foreach ($dispMesas as $mesaId => $hora) {
                    if ($hora < $horaLivreMesa) {
                        $horaLivreMesa = $hora;
                        $melhorMesa = $mesaId;
                    }
                }

                $melhorJogoIndex = -1;
                $menorAtraso = PHP_INT_MAX;
                $melhorHoraInicioJogo = PHP_INT_MAX;

                foreach ($jogosMataMata as $index => $jogo) {
                    $idA = $jogo['atleta_a_id'];
                    $idB = $jogo['atleta_b_id'];

                    $horaLivreA = $dispAtletas[$idA] ?? $horaMaisTardeDeTodasAsMesas;
                    $horaLivreB = $dispAtletas[$idB] ?? $horaMaisTardeDeTodasAsMesas;

                    $horaLivreAmbos = max($horaLivreA, $horaLivreB);
                    $horaInicioReal = max($horaLivreMesa, $horaLivreAmbos);
                    $atraso = $horaInicioReal - $horaLivreMesa;

                    if ($atraso < $menorAtraso) {
                        $menorAtraso = $atraso;
                        $melhorJogoIndex = $index;
                        $melhorHoraInicioJogo = $horaInicioReal;
                    }
                    if ($atraso == 0) break; 
                }

                $jogoEscolhido = $jogosMataMata[$melhorJogoIndex];
                $jogosAgendados[] = [
                    'id' => $jogoEscolhido['id'],
                    'mesa' => $melhorMesa,
                    'horario_previsto' => date('H:i:s', $melhorHoraInicioJogo)
                ];

                $horaFimJogo = $melhorHoraInicioJogo + 1800;
                $dispMesas[$melhorMesa] = $horaFimJogo;
                if ($jogoEscolhido['atleta_a_id']) $dispAtletas[$jogoEscolhido['atleta_a_id']] = $horaFimJogo;
                if ($jogoEscolhido['atleta_b_id']) $dispAtletas[$jogoEscolhido['atleta_b_id']] = $horaFimJogo;

                unset($jogosMataMata[$melhorJogoIndex]);
                $jogosMataMata = array_values($jogosMataMata);
            }
        }

        $update = new \App\adms\Models\helper\AdmsUpdate();
        $jogosAtualizados = 0;

        foreach ($jogosAgendados as $agendado) {
            $dadosUpdate = [
                'mesa' => $agendado['mesa'],
                'status_partida' => 'Agendado',
                'horario_previsto' => $agendado['horario_previsto']
            ];

            $update->exeUpdate("adms_partidas", $dadosUpdate, "WHERE id = :id", "id={$agendado['id']}");
            if ($update->getResult()) {
                $jogosAtualizados++;
            }
        }

        if ($jogosAtualizados > 0) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso! {$jogosAtualizados} jogos foram processados e distribuídos de forma otimizada em {$qtdMesas} mesas simultâneas.</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro interno: O banco de dados recusou a atualização.</p>";
            $this->result = false;
        }
    }

    public function listarArbitros(): array|null
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead(
            "SELECT id, name FROM adms_users WHERE adms_access_level_id = 15 AND empresa_id = :empresa ORDER BY name ASC", 
            "empresa={$_SESSION['emp_user']}"
        );
        return $list->getResult();
    }

    public function atribuirArbitro(int $compId, array $dados): void
    {
        $mesa = (int)$dados['mesa'];
        $arbitroId = !empty($dados['arbitro_id']) ? (int)$dados['arbitro_id'] : null;

        if ($arbitroId !== null) {
            $read = new \App\adms\Models\helper\AdmsRead();

            $read->fullRead(
                "SELECT horario_previsto FROM adms_partidas 
                 WHERE adms_competicao_id = :comp AND mesa = :mesa AND status_partida = 'Agendado' AND horario_previsto IS NOT NULL",
                "comp={$compId}&mesa={$mesa}"
            );
            $jogosMesaAlvo = $read->getResult();

            $read->fullRead(
                "SELECT mesa, horario_previsto FROM adms_partidas 
                 WHERE adms_competicao_id = :comp AND arbitro_id = :arbitro_id AND mesa != :mesa AND status_partida = 'Agendado' AND horario_previsto IS NOT NULL",
                "comp={$compId}&arbitro_id={$arbitroId}&mesa={$mesa}"
            );
            $jogosOutrasMesas = $read->getResult();

            if ($jogosMesaAlvo && $jogosOutrasMesas) {
                foreach ($jogosMesaAlvo as $alvo) {
                    $timeAlvo = strtotime($alvo['horario_previsto']);
                    
                    foreach ($jogosOutrasMesas as $arb) {
                        $timeArb = strtotime($arb['horario_previsto']);
                        
                        if (abs($timeAlvo - $timeArb) < 1800) {
                            $horaFormatada = date('H:i', $timeArb);
                            $_SESSION['msg'] = "<p class='alert-danger'><b>Aviso:</b> O árbitro selecionado já possui um jogo na <b>Mesa {$arb['mesa']} às {$horaFormatada}</b>. Não é possível escalá-lo para a Mesa {$mesa} neste mesmo período.</p>";
                            $this->result = false;
                            return; 
                        }
                    }
                }
            }
        }

        $update = new \App\adms\Models\helper\AdmsUpdate();
        
        $update->exeUpdate(
            "adms_partidas", 
            ['arbitro_id' => $arbitroId], 
            "WHERE adms_competicao_id = :comp AND mesa = :mesa AND status_partida = 'Agendado'", 
            "comp={$compId}&mesa={$mesa}"
        );

        if ($update->getResult()) {
            if ($arbitroId) {
                $_SESSION['msg'] = "<p class='alert-success'>Sucesso! Árbitro escalado para a Mesa {$mesa}.</p>";
            } else {
                $_SESSION['msg'] = "<p class='alert-success'>Sucesso! A Mesa {$mesa} ficou sem árbitro fixo.</p>";
            }
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ao atualizar a mesa.</p>";
            $this->result = false;
        }
    }

    // =========================================================================
    // CORREÇÃO AQUI: INNER JOIN com adms_categorias em vez de adms_divisoes!
    // =========================================================================
    public function listarAgenda(int $compId): array|null
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead(
            "SELECT p.id, p.mesa, p.status_partida, p.fase, p.horario_previsto, p.arbitro_id,
                    ua.name as atleta_a, ub.name as atleta_b, c.nome as cat_nome,
                    arb.name as arbitro_nome
             FROM adms_partidas p
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             LEFT JOIN adms_categorias c ON c.id = p.adms_categoria_id
             LEFT JOIN adms_users arb ON arb.id = p.arbitro_id 
             WHERE p.adms_competicao_id = :comp_id AND (p.vencedor_id IS NULL OR p.vencedor_id = 0) AND p.mesa IS NOT NULL AND p.mesa > 0
             ORDER BY p.mesa ASC, p.horario_previsto ASC, p.id ASC", 
            "comp_id={$compId}"
        );
        return $list->getResult();
    }
}