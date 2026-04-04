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
        
        // Busca o nome do torneio e detalhes
        $read->fullRead("SELECT nome_torneio FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $torneio = $read->getResult()[0] ?? null;
        
        // Busca os jogos pendentes que já têm mesa atribuída
        $read->fullRead(
            "SELECT p.id, p.mesa, p.status_partida, p.fase, p.horario_previsto, 
                    ua.name as atleta_a, ub.name as atleta_b, cat.nome as cat_nome
             FROM adms_partidas p
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             WHERE p.adms_competicao_id = :comp_id 
               AND (p.vencedor_id IS NULL OR p.vencedor_id = 0) 
               AND p.mesa IS NOT NULL AND p.mesa > 0
             ORDER BY p.mesa ASC, p.horario_previsto ASC",
            "comp_id={$compId}"
        );

        // Pega apenas o PRÓXIMO jogo de cada mesa para não poluir a tela
        $jogosGerais = $read->getResult() ?: [];
        $jogosNoPainel = [];
        $mesasOcupadas = [];

        foreach ($jogosGerais as $jogo) {
            if (!in_array($jogo['mesa'], $mesasOcupadas)) {
                $jogosNoPainel[] = $jogo;
                $mesasOcupadas[] = $jogo['mesa'];
            }
        }

        $this->result = [
            'nome_torneio' => $torneio['nome_torneio'] ?? 'Torneio TMNet',
            'jogos' => $jogosNoPainel
        ];
    }
}