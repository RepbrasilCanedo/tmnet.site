<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAvancarMataMata
{
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function avancarFase(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca a fase mais avançada agrupada por CATEGORIA E GÊNERO
        $read->fullRead(
            "SELECT p.fase, p.adms_categoria_id, p.genero_partida, cat.nome as cat_nome
             FROM adms_partidas p
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             WHERE p.adms_competicao_id = :comp_id AND p.fase NOT LIKE 'Grupo%' AND p.fase != 'Classificatória'
             GROUP BY p.adms_categoria_id, p.genero_partida, p.fase 
             ORDER BY MAX(p.id) DESC", 
            "comp_id={$compId}"
        );
        
        $fasesExistentes = $read->getResult();

        if (!$fasesExistentes) {
            $_SESSION['msg'] = "<p class='alert-warning'>Nenhum jogo de eliminatória encontrado. Gere o Mata-Mata inicial primeiro.</p>";
            return;
        }

        $create = new \App\adms\Models\helper\AdmsCreate();
        $totalNovosJogos = 0;

        // 2. Filtra apenas a última fase de cada combinação (Categoria + Gênero)
        $combinacoesEncontradas = [];
        foreach($fasesExistentes as $f){
            $catId = $f['adms_categoria_id'] ?? 0;
            $genId = $f['genero_partida'] ?? 'X';

            if (!isset($combinacoesEncontradas[$catId][$genId])) {
                $combinacoesEncontradas[$catId][$genId] = $f['fase'];
            }
        }

        // 3. Processa cada Categoria e Gênero de forma independente
        foreach ($combinacoesEncontradas as $catId => $generos) {
            foreach ($generos as $genId => $faseAtual) {
                
                if ($faseAtual == 'Final') continue;

                $read->fullRead(
                    "SELECT vencedor_id, status_partida FROM adms_partidas 
                     WHERE adms_competicao_id = :comp_id AND fase = :fase 
                       AND (adms_categoria_id = :cat_id OR (:cat_id2 = 0 AND adms_categoria_id IS NULL))
                       AND genero_partida = :gen_id
                     ORDER BY id ASC", 
                    "comp_id={$compId}&fase={$faseAtual}&cat_id={$catId}&cat_id2={$catId}&gen_id={$genId}"
                );
                $jogosSet = $read->getResult();

                $vencedores = [];
                $pendente = false;
                foreach ($jogosSet as $j) {
                    if (empty($j['vencedor_id']) || $j['status_partida'] !== 'Finalizado') {
                        $pendente = true;
                        break;
                    }
                    $vencedores[] = $j['vencedor_id'];
                }

                // Se houver algum jogo pendente na categoria/fase, não avança
                if ($pendente) continue;

                $qtdV = count($vencedores);
                if ($qtdV < 2) continue;

                $proximaFase = "Final"; 
                if ($qtdV > 2 && $qtdV <= 4) {
                    $proximaFase = "Semifinal"; 
                } elseif ($qtdV > 4 && $qtdV <= 8) {
                    $proximaFase = "Quartas de Final"; 
                } elseif ($qtdV > 8 && $qtdV <= 16) {
                    $proximaFase = "Oitavas de Final";
                }

                // Verifica se a próxima fase já foi criada para esta Categoria/Gênero para não duplicar
                $read->fullRead(
                    "SELECT id FROM adms_partidas 
                     WHERE adms_competicao_id = :comp_id AND fase = :prox 
                       AND (adms_categoria_id = :cat_id OR (:cat_id2 = 0 AND adms_categoria_id IS NULL))
                       AND genero_partida = :gen_id LIMIT 1",
                    "comp_id={$compId}&prox={$proximaFase}&cat_id={$catId}&cat_id2={$catId}&gen_id={$genId}"
                );

                if ($read->getResult()) continue;

                // Cria os novos confrontos
                for ($i = 0; $i < $qtdV; $i += 2) {
                    if (isset($vencedores[$i + 1])) {
                        $create->exeCreate("adms_partidas", [
                            'adms_competicao_id' => $compId,
                            'adms_categoria_id' => ($catId > 0) ? $catId : null,
                            'genero_partida' => $genId,
                            'atleta_a_id' => $vencedores[$i],
                            'atleta_b_id' => $vencedores[$i + 1],
                            'fase' => $proximaFase,
                            'status_partida' => 'Agendado',
                            'created' => date("Y-m-d H:i:s")
                        ]);
                        $totalNovosJogos++;
                    }
                }
            }
        }

        if ($totalNovosJogos > 0) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso! Gerados {$totalNovosJogos} novos jogos para as próximas fases. <b>Vá à Agenda para distribuir as mesas.</b></p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-warning'>Não há novos vencedores suficientes ou jogos pendentes para avançar fase nas categorias atuais.</p>";
        }
    }
}