<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsPainelJogos
{
    private array|null $result;

    function getResult(): array|null { return $this->result; }

    public function listarJogosPainel(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT nome_torneio FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $torneio = $read->getResult()[0] ?? null;

        // 1. Verifica se o torneio já acabou por completo (Zero jogos pendentes)
        $read->fullRead("SELECT id FROM adms_partidas WHERE adms_competicao_id = :comp_id AND (vencedor_id IS NULL OR vencedor_id = 0) LIMIT 1", "comp_id={$compId}");
        $isFinished = empty($read->getResult()); 
        
        // ========================================================================
        // DOCAN FIX: Busca os jogos e agora puxa todos os dados do Placar (Sets e Pontos)
        // ========================================================================
        $read->fullRead(
            "SELECT p.id, p.mesa, p.status_partida, p.fase, p.horario_previsto, 
                    COALESCE(ua.name, 'A Definir') as atleta_a, 
                    COALESCE(ub.name, 'A Definir') as atleta_b, 
                    cat.nome as cat_nome,
                    p.sets_atleta_a, p.sets_atleta_b,
                    p.pts_set1_a, p.pts_set1_b, p.pts_set2_a, p.pts_set2_b,
                    p.pts_set3_a, p.pts_set3_b, p.pts_set4_a, p.pts_set4_b,
                    p.pts_set5_a, p.pts_set5_b
             FROM adms_partidas p
             LEFT JOIN adms_users ua ON ua.id = p.atleta_a_id
             LEFT JOIN adms_users ub ON ub.id = p.atleta_b_id
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             WHERE p.adms_competicao_id = :comp_id 
               AND (p.vencedor_id IS NULL OR p.vencedor_id = 0) 
               AND p.mesa IS NOT NULL AND p.mesa > 0
             ORDER BY p.mesa ASC, p.horario_previsto ASC",
            "comp_id={$compId}"
        );

        $jogosGerais = $read->getResult() ?: [];
        $jogosNoPainel = [];
        $mesasOcupadas = [];

        foreach ($jogosGerais as $jogo) {
            if (!in_array($jogo['mesa'], $mesasOcupadas)) {
                
                // DOCAN LÓGICA: Descobre qual é o set atual que está a ser disputado!
                $ptsA = 0; $ptsB = 0; $setAtual = 1;
                for ($i = 5; $i >= 1; $i--) {
                    if ($jogo["pts_set{$i}_a"] !== null || $jogo["pts_set{$i}_b"] !== null) {
                        $ptsA = (int)$jogo["pts_set{$i}_a"];
                        $ptsB = (int)$jogo["pts_set{$i}_b"];
                        $setAtual = $i;
                        break;
                    }
                }
                
                $jogo['pts_a'] = $ptsA;
                $jogo['pts_b'] = $ptsB;
                $jogo['set_atual'] = $setAtual;
                $jogo['sets_atleta_a'] = (int)($jogo['sets_atleta_a'] ?? 0);
                $jogo['sets_atleta_b'] = (int)($jogo['sets_atleta_b'] ?? 0);

                $jogosNoPainel[] = $jogo;
                $mesasOcupadas[] = $jogo['mesa'];
            }
        }

        // =========================================================
        // 3. Busca Pódios: 1º e 2º (Finais) + 3ºs Lugares (Semifinais)
        // =========================================================
        $read->fullRead(
            "SELECT p.id, p.fase, p.vencedor_id, p.atleta_a_id, p.atleta_b_id, p.genero_partida,
                    ua.name as atleta_a, ub.name as atleta_b,
                    uv.name as vencedor_nome, cat.nome as cat_nome, c.tipo_genero
             FROM adms_partidas p
             LEFT JOIN adms_users ua ON ua.id = p.atleta_a_id
             LEFT JOIN adms_users ub ON ub.id = p.atleta_b_id
             LEFT JOIN adms_users uv ON uv.id = p.vencedor_id
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             WHERE p.adms_competicao_id = :comp_id 
               AND p.fase IN ('Final', 'Semifinal') 
               AND p.vencedor_id IS NOT NULL AND p.vencedor_id > 0",
            "comp_id={$compId}"
        );
        $jogosDecisivos = $read->getResult() ?: [];
        $podiosPorCategoria = [];
        
        foreach ($jogosDecisivos as $jogo) {
            $catNomeBase = !empty($jogo['cat_nome']) ? $jogo['cat_nome'] : 'Livre';
            
            $tipoGenero = $jogo['tipo_genero'] ?? 1;
            $genNome = 'Misto';
            if ($tipoGenero == 2) {
                $genNome = ($jogo['genero_partida'] == 'F') ? 'Feminino' : 'Masculino';
            }
            
            $catNomeCompleto = $catNomeBase . " - " . $genNome;

            if (!isset($podiosPorCategoria[$catNomeCompleto])) {
                $podiosPorCategoria[$catNomeCompleto] = [
                    'categoria' => $catNomeCompleto,
                    'campeao' => 'A Definir',
                    'vice' => 'A Definir',
                    'terceiros' => []
                ];
            }

            if ($jogo['fase'] == 'Final') {
                $campeao = $jogo['vencedor_nome'];
                $vice = ($jogo['vencedor_id'] == $jogo['atleta_a_id']) ? $jogo['atleta_b'] : $jogo['atleta_a'];
                $podiosPorCategoria[$catNomeCompleto]['campeao'] = $campeao;
                $podiosPorCategoria[$catNomeCompleto]['vice'] = $vice;
            } elseif ($jogo['fase'] == 'Semifinal') {
                $terceiro = ($jogo['vencedor_id'] == $jogo['atleta_a_id']) ? $jogo['atleta_b'] : $jogo['atleta_a'];
                $podiosPorCategoria[$catNomeCompleto]['terceiros'][] = $terceiro;
            }
        }

        $this->result = [
            'nome_torneio' => $torneio['nome_torneio'] ?? 'Torneio TMNet',
            'is_finished' => $isFinished,
            'jogos' => $jogosNoPainel,
            'podios' => array_values($podiosPorCategoria)
        ];
    }
}