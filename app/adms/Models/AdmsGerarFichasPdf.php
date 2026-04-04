<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsGerarFichasPdf
{
    private array|null $result;

    function getResult(): array|null { return $this->result; }

    public function buscarJogosAgendados(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // Busca os jogos pendentes, incluindo a CATEGORIA e o Horário Previsto
        $read->fullRead(
            "SELECT p.id, p.fase, p.mesa, p.horario_previsto, ua.name as atleta_a, ub.name as atleta_b, c.nome_torneio, cat.nome as cat_nome
             FROM adms_partidas p
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             LEFT JOIN adms_categorias cat ON cat.id = p.adms_categoria_id
             WHERE p.adms_competicao_id = :comp_id AND p.status_partida = 'Agendado'
             ORDER BY p.mesa ASC, p.horario_previsto ASC, p.id ASC",
            "comp_id={$compId}"
        );

        $this->result = $read->getResult();
    }
}