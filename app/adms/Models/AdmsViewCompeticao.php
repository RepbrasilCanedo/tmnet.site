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
        
        // 1. Busca detalhes da competição
        $view->fullRead("SELECT * FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", 
                        "id={$id}&empresa={$_SESSION['emp_user']}");
        $this->result['detalhes'] = $view->getResult()[0] ?? null;

        if ($this->result['detalhes']) {
            // 2. Busca todas as partidas deste torneio (Atualizado para CATEGORIAS)
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

            // =========================================================================
            // RASTREADOR DE PROGRESSO DO TORNEIO (TRAVAS DE SEGURANÇA)
            // =========================================================================
            $statusProgresso = [
                'has_grupos' => false,
                'has_matamata' => false,
                'is_finished' => false,
                'total_finais' => 0,
                'finais_concluidas' => 0
            ];

            if (!empty($this->result['partidas'])) {
                foreach ($this->result['partidas'] as $p) {
                    if (stripos($p['fase'], 'Grupo') !== false || stripos($p['fase'], 'Classificat') !== false) {
                        $statusProgresso['has_grupos'] = true;
                    } else {
                        $statusProgresso['has_matamata'] = true;
                    }

                    if ($p['fase'] === 'Final') {
                        $statusProgresso['total_finais']++;
                        if (!empty($p['vencedor_id']) && $p['vencedor_id'] > 0) {
                            $statusProgresso['finais_concluidas']++;
                        }
                    }
                }
            }

            // Se existem finais e todas elas têm vencedor, o torneio acabou!
            if ($statusProgresso['total_finais'] > 0 && $statusProgresso['total_finais'] === $statusProgresso['finais_concluidas']) {
                $statusProgresso['is_finished'] = true;
            }

            $this->result['status_progresso'] = $statusProgresso;
            // =========================================================================

            // 3. INTELIGÊNCIA DO PÓDIO (Separado por Categoria E Gênero)
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
}