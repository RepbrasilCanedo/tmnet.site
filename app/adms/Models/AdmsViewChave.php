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
        
        $view->fullRead("SELECT nome_torneio FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", 
                        "id={$compId}&empresa={$_SESSION['emp_user']}");
        $this->result['detalhes'] = $view->getResult()[0] ?? null;

        if (!$this->result['detalhes']) {
            $this->result = null;
            return;
        }

        // DOCAN: Alterado para LEFT JOIN nos atletas e categorias para os jogos "A Definir" não sumirem!
        $view->fullRead(
            "SELECT p.id, p.fase, p.atleta_a_id, p.atleta_b_id, p.vencedor_id, 
                    p.sets_atleta_a, p.sets_atleta_b,
                    ua.name as atleta_a_nome, ua.apelido as atleta_a_apelido,
                    ub.name as atleta_b_nome, ub.apelido as atleta_b_apelido,
                    uv.name as vencedor_nome, cat.nome as cat_nome, p.genero_partida, c.tipo_genero
             FROM adms_partidas p
             LEFT JOIN adms_users ua ON ua.id = p.atleta_a_id
             LEFT JOIN adms_users ub ON ub.id = p.atleta_b_id
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             LEFT JOIN adms_users uv ON uv.id = p.vencedor_id
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             WHERE p.adms_competicao_id = :comp_id 
               AND p.fase NOT LIKE '%Grupo%' AND p.fase NOT LIKE '%Classificató%'
             ORDER BY p.id ASC", 
             "comp_id={$compId}"
        );
        $todasPartidas = $view->getResult();

        $chaveMataMata = [];

        if ($todasPartidas) {
            foreach ($todasPartidas as $partida) {
                
                $divName = $partida['cat_nome'] ?? 'Categoria Livre';
                $tipoGenero = $partida['tipo_genero'] ?? 1;
                $genNome = 'Misto';
                if ($tipoGenero == 2) {
                    $genNome = ($partida['genero_partida'] == 'F') ? 'Feminino' : 'Masculino';
                }
                
                $chavePodio = $divName . " - " . $genNome;
                $nomeFase = $partida['fase'];

                $ordem = 100;
                if (stripos($nomeFase, 'Final') !== false && stripos($nomeFase, 'Semi') === false) $ordem = 10;
                elseif (stripos($nomeFase, 'Semi') !== false) $ordem = 20;
                elseif (stripos($nomeFase, 'Quartas') !== false) $ordem = 30;
                elseif (stripos($nomeFase, 'Oitavas') !== false) $ordem = 40;
                elseif (stripos($nomeFase, 'Dezesseis') !== false) $ordem = 50;

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

                $chaveMataMata[$chavePodio]['fases'][$nomeFase]['jogos'][] = $partida;
            }

            foreach ($chaveMataMata as $key => $categoria) {
                uasort($chaveMataMata[$key]['fases'], function($a, $b) {
                    return $b['ordem'] <=> $a['ordem']; 
                });
            }
        }

        $this->result['chave'] = $chaveMataMata;
    }
}