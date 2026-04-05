<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsRankingGeral
{
    private array|null $result = [];

    function getResult(): array|null { return $this->result; }

    public function listarRanking(array|null $search = null): void
    {
        $listar = new \App\adms\Models\helper\AdmsRead();
        $empresaId = $_SESSION['emp_user'];

        // 1. Busca todas as CATEGORIAS do Clube
        $listar->fullRead("SELECT id, nome, idade_minima, idade_maxima, pontuacao_maxima FROM adms_categorias WHERE empresa_id = :empresa ORDER BY pontuacao_maxima DESC, nome ASC", "empresa={$empresaId}");
        $categorias = $listar->getResult() ?: [];

        // 2. Busca todos os atletas (AGORA COM MÃO DOMINANTE)
        if (!empty($search['search_nome'])) {
            $listar->fullRead(
                "SELECT id, name AS nome, apelido, imagem, estilo_jogo, mao_dominante, pontuacao_ranking, genero, data_nascimento 
                 FROM adms_users 
                 WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
                 AND (name LIKE :search OR apelido LIKE :search)
                 ORDER BY pontuacao_ranking DESC", 
                "empresa={$empresaId}&search=%{$search['search_nome']}%"
            );
        } else {
            $listar->fullRead(
                "SELECT id, name AS nome, apelido, imagem, estilo_jogo, mao_dominante, pontuacao_ranking, genero, data_nascimento 
                 FROM adms_users 
                 WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
                 ORDER BY pontuacao_ranking DESC", 
                "empresa={$empresaId}"
            );
        }

        $atletas = $listar->getResult() ?: [];

        // 3. Estruturas de Dados para a View
        $rankingGeral = [];
        $rankingPorCategoria = [];

        // Prepara as abas de categorias
        foreach ($categorias as $cat) {
            $rankingPorCategoria[$cat['id']] = [
                'nome_categoria' => $cat['nome'],
                'geral' => [],
                'generos' => []
            ];
        }

        if ($atletas) {
            foreach ($atletas as $atleta) {
                // A. Adiciona todos ao Ranking Geral
                $rankingGeral[] = $atleta;

                // Calcula a Idade do Atleta
                $idade = 0;
                if (!empty($atleta['data_nascimento'])) {
                    $nasc = new \DateTime($atleta['data_nascimento']);
                    $hoje = new \DateTime('today');
                    $idade = $nasc->diff($hoje)->y;
                }
                
                $pontos = (float)$atleta['pontuacao_ranking'];
                $genero = $atleta['genero'] ?? 'M';
                $generoNome = ($genero == 'F') ? 'Feminino' : 'Masculino';

                // B. Verifica em quais Categorias o atleta se encaixa
                foreach ($categorias as $cat) {
                    $apto = true;
                    
                    $idadeMin = (is_numeric($cat['idade_minima']) && $cat['idade_minima'] > 0) ? (int)$cat['idade_minima'] : null;
                    $idadeMax = (is_numeric($cat['idade_maxima']) && $cat['idade_maxima'] > 0) ? (int)$cat['idade_maxima'] : null;
                    $pontosMax = (is_numeric($cat['pontuacao_maxima']) && $cat['pontuacao_maxima'] > 0) ? (float)$cat['pontuacao_maxima'] : null;

                    if ($idadeMin !== null && $idade < $idadeMin) $apto = false;
                    if ($idadeMax !== null && $idade > $idadeMax) $apto = false;
                    if ($pontosMax !== null && $pontos > $pontosMax) $apto = false;

                    if ($apto) {
                        $rankingPorCategoria[$cat['id']]['geral'][] = $atleta; 
                        $rankingPorCategoria[$cat['id']]['generos'][$generoNome][] = $atleta; 
                    }
                }
            }
        }

        // Remove as categorias que não têm nenhum atleta para não sujar a tela
        foreach ($rankingPorCategoria as $id => $dados) {
            if (empty($dados['geral'])) {
                unset($rankingPorCategoria[$id]);
            }
        }

        $this->result['geral'] = $rankingGeral;
        $this->result['por_categoria'] = $rankingPorCategoria;
    }
}