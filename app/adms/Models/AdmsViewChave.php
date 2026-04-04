<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsViewChave
{
    private array|null $result;
    private array $ordemFases = [];

    function getResult(): array|null { return $this->result; }

    public function viewChave(int $compId): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca detalhes da competição
        $view->fullRead("SELECT nome_torneio FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", 
                        "id={$compId}&empresa={$_SESSION['emp_user']}");
        $this->result['detalhes'] = $view->getResult()[0] ?? null;

        if (!$this->result['detalhes']) {
            $this->result = null;
            return;
        }

        // 2. Busca TODAS as partidas de MATA-MATA desta competição (Não pega Fase de Grupos)
        $view->fullRead(
            "SELECT p.id, p.fase, p.atleta_a_id, p.atleta_b_id, p.vencedor_id, 
                    p.sets_atleta_a, p.sets_atleta_b,
                    ua.name as atleta_a_nome, ua.apelido as atleta_a_apelido,
                    ub.name as atleta_b_nome, ub.apelido as atleta_b_apelido,
                    uv.name as vencedor_nome, d.nome as div_nome, p.genero_partida, c.tipo_genero
             FROM adms_partidas p
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             LEFT JOIN adms_users uv ON uv.id = p.vencedor_id
             LEFT JOIN adms_divisoes d ON d.id = p.adms_divisao_id
             WHERE p.adms_competicao_id = :comp_id 
               AND p.fase NOT LIKE '%Grupo%' AND p.fase NOT LIKE '%Classificató%'
             ORDER BY p.id ASC", // Importante ordenar por ID para manter a ordem de geração
             "comp_id={$compId}"
        );
        $todasPartidas = $view->getResult();

        $chaveMataMata = [];

        if ($todasPartidas) {
            // 3. ORGANIZADOR INTELIGENTE DE ÁRVORE
            // Agrupa os jogos por Divisão e Gênero (Ex: Iniciante Misto, Avançado Masculino)
            foreach ($todasPartidas as $partida) {
                
                $divName = $partida['div_nome'] ?? 'Categoria Livre';
                $tipoGenero = $partida['tipo_genero'] ?? 1;
                $genNome = 'Misto';
                if ($tipoGenero == 2) {
                    $genNome = ($partida['genero_partida'] == 'F') ? 'Feminino' : 'Masculino';
                }
                
                $chavePodio = $divName . " - " . $genNome;
                $nomeFase = $partida['fase'];

                // Define a ordem visual das colunas
                $ordem = 100; // Padrão para fases desconhecidas
                if (stripos($nomeFase, 'Final') !== false && stripos($nomeFase, 'Semi') === false) $ordem = 10;
                elseif (stripos($nomeFase, 'Semi') !== false) $ordem = 20;
                elseif (stripos($nomeFase, 'Quartas') !== false) $ordem = 30;
                elseif (stripos($nomeFase, 'Oitavas') !== false) $ordem = 40;
                elseif (stripos($nomeFase, 'Dezesseis') !== false) $ordem = 50;

                // Prepara a estrutura do Array
                if (!isset($chaveMataMata[$chavePodio])) {
                    $chaveMataMata[$chavePodio] = [
                        'titulo' => $chavePodio,
                        'fases' => []
                    ];
                }

                if (!isset($chaveMataMata[$chavePodio]['fases'][$nomeFase])) {
                    $chaveMataMata[$chavePodio]['fases'][$nomeFase] = [
                        'nome' => $nomeFase,
                        'ordem' => $ordem,
                        'jogos' => []
                    ];
                }

                // Adiciona o jogo à fase correspondente
                $chaveMataMata[$chavePodio]['fases'][$nomeFase]['jogos'][] = $partida;
            }

            // 4. ORDENAÇÃO DAS COLUNAS
            // Garante que a Final fique na direita, Semis antes, Quartas antes, etc.
            foreach ($chaveMataMata as $key => $categoria) {
                uasort($chaveMataMata[$key]['fases'], function($a, $b) {
                    return $b['ordem'] <=> $a['ordem']; // Inverso: Maior ordem (Oitavas) na esquerda, menor (Final) na direita
                });
            }
        }

        $this->result['chave'] = $chaveMataMata;
    }
}