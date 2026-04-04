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

        // 1. Busca todas as divisões do Clube
        $listar->fullRead("SELECT id, nome, pontuacao_min, pontuacao_max FROM adms_divisoes WHERE empresa_id = :empresa ORDER BY pontuacao_min ASC", "empresa={$empresaId}");
        $divisoes = $listar->getResult() ?: [];

        // 2. Busca todos os atletas (com ou sem pesquisa)
        if (!empty($search['search_nome'])) {
            $listar->fullRead(
                "SELECT id, name AS nome, apelido, imagem, estilo_jogo, pontuacao_ranking, genero 
                 FROM adms_users 
                 WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
                 AND (name LIKE :search OR apelido LIKE :search)
                 ORDER BY pontuacao_ranking DESC", 
                "empresa={$empresaId}&search=%{$search['search_nome']}%"
            );
        } else {
            $listar->fullRead(
                "SELECT id, name AS nome, apelido, imagem, estilo_jogo, pontuacao_ranking, genero 
                 FROM adms_users 
                 WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
                 ORDER BY pontuacao_ranking DESC", 
                "empresa={$empresaId}"
            );
        }

        $atletas = $listar->getResult();

        // 3. Estruturas de Dados para a View
        $rankingGeral = [];
        $rankingPorDivisao = [];

        if ($atletas) {
            foreach ($atletas as $atleta) {
                // A. Adiciona ao Ranking Geral
                $rankingGeral[] = $atleta;

                // B. Descobre a Divisão do Atleta
                $divNomeAtleta = "Sem Divisão";
                $divIdAtleta = 0;
                
                foreach ($divisoes as $div) {
                    if ($atleta['pontuacao_ranking'] >= $div['pontuacao_min'] && $atleta['pontuacao_ranking'] <= $div['pontuacao_max']) {
                        $divNomeAtleta = $div['nome'];
                        $divIdAtleta = $div['id'];
                        break;
                    }
                }

                // C. Adiciona ao Ranking Por Divisão (Separado por Gênero)
                $genero = $atleta['genero'] ?? 'M';
                $generoNome = ($genero == 'F') ? 'Feminino' : 'Masculino';

                $rankingPorDivisao[$divIdAtleta]['nome_divisao'] = $divNomeAtleta;
                $rankingPorDivisao[$divIdAtleta]['geral'][] = $atleta; // Todos juntos na divisão
                $rankingPorDivisao[$divIdAtleta]['generos'][$generoNome][] = $atleta; // Separados por sexo
            }
        }

        $this->result['geral'] = $rankingGeral;
        $this->result['por_divisao'] = $rankingPorDivisao;
    }
}