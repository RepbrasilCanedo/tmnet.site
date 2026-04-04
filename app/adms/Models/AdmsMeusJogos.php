<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsMeusJogos
{
    private array|null $result = null;

    function getResult(): array|null { return $this->result; }

    public function listarJogos(int $arbitroId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // Busca as partidas agendadas exclusivas deste árbitro
        $read->fullRead(
            "SELECT p.id, p.mesa, p.fase, p.horario_previsto, 
                    ua.name as atleta_a, ub.name as atleta_b, 
                    c.nome_torneio, d.nome as div_nome
             FROM adms_partidas p
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             LEFT JOIN adms_divisoes d ON d.id = p.adms_divisao_id
             WHERE p.arbitro_id = :arbitro_id AND p.status_partida = 'Agendado'
             ORDER BY p.horario_previsto ASC, p.id ASC",
            "arbitro_id={$arbitroId}"
        );

        $this->result = $read->getResult();
    }
}