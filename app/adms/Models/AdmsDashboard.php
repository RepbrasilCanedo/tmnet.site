<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsDashboard
{
    private array|null $result;

    function getResult(): array|null { return $this->result; }

    // ========================================================================
    // ESTATÍSTICAS DO ADMINISTRADOR
    // ========================================================================
    public function getEstatisticas(): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $empresaId = $_SESSION['emp_user'];
        
        $read->fullRead("SELECT COUNT(id) as total_atletas FROM adms_users WHERE adms_access_level_id = 14 AND empresa_id = :empresa", "empresa={$empresaId}");
        $this->result['contagem']['atletas'] = $read->getResult()[0]['total_atletas'] ?? 0;

        $read->fullRead("SELECT COUNT(id) as total_comp FROM adms_competicoes WHERE empresa_id = :empresa", "empresa={$empresaId}");
        $this->result['contagem']['competicoes'] = $read->getResult()[0]['total_comp'] ?? 0;

        $read->fullRead(
            "SELECT COUNT(p.id) as total_partidas FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             WHERE c.empresa_id = :empresa AND p.status_partida = 'Finalizado'", 
            "empresa={$empresaId}"
        );
        $this->result['contagem']['partidas'] = $read->getResult()[0]['total_partidas'] ?? 0;

        $read->fullRead(
            "SELECT id, name AS nome, apelido, imagem, pontuacao_ranking 
             FROM adms_users 
             WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
             ORDER BY pontuacao_ranking DESC LIMIT 1", 
            "empresa={$empresaId}"
        );
        $this->result['lider'] = $read->getResult()[0] ?? null;
    }

    // ========================================================================
    // ESTATÍSTICAS DO ÁRBITRO (NOVO)
    // ========================================================================
    public function getEstatisticasArbitro(int $arbitroId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Total de jogos apitados na carreira
        $read->fullRead(
            "SELECT COUNT(id) as total_apitos FROM adms_partidas 
             WHERE arbitro_id = :arbitro_id AND status_partida = 'Finalizado'",
            "arbitro_id={$arbitroId}"
        );
        $this->result['arbitro_stats']['total_apitos'] = $read->getResult()[0]['total_apitos'] ?? 0;

        // 2. Próximos Jogos (Agenda do Árbitro)
        $read->fullRead(
            "SELECT p.id, p.mesa, p.horario_previsto, p.fase, p.adms_competicao_id,
                    ua.name as atleta_a, ub.name as atleta_b, 
                    c.nome_torneio, c.data_evento 
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             WHERE p.arbitro_id = :arbitro_id AND p.status_partida = 'Agendado'
             ORDER BY c.data_evento ASC, p.horario_previsto ASC",
            "arbitro_id={$arbitroId}"
        );
        $this->result['arbitro_proximos'] = $read->getResult();

        // 3. Histórico Recente (Últimos jogos finalizados)
        $read->fullRead(
            "SELECT p.id, p.fase, p.sets_atleta_a, p.sets_atleta_b, p.vencedor_id, p.atleta_a_id, p.is_wo,
                    ua.name as atleta_a, ub.name as atleta_b, 
                    c.nome_torneio 
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             WHERE p.arbitro_id = :arbitro_id AND p.status_partida = 'Finalizado'
             ORDER BY p.id DESC LIMIT 15",
            "arbitro_id={$arbitroId}"
        );
        $this->result['arbitro_historico'] = $read->getResult();
    }
}