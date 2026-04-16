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
    // ESTATÍSTICAS DO ADMINISTRADOR E PLATAFORMA (VISÃO DIVIDIDA)
    // ========================================================================
    public function getEstatisticas(): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $empresaId = (int)$_SESSION['emp_user'];
        $nivelLogado = (int)$_SESSION['adms_access_level_id'];
        
        // DOCAN LOGIC: BIFURCAÇÃO DE VISIBILIDADE
        if ($nivelLogado <= 2) {
            // =========================================================
            // VISÃO DA PLATAFORMA (S-ADMIN) - Vê Universo Inteiro
            // =========================================================
            $read->fullRead("SELECT COUNT(id) as total_atletas FROM adms_users WHERE adms_access_level_id = 14");
            $this->result['contagem']['atletas'] = $read->getResult()[0]['total_atletas'] ?? 0;

            $read->fullRead("SELECT COUNT(id) as total_comp FROM adms_competicoes");
            $this->result['contagem']['competicoes'] = $read->getResult()[0]['total_comp'] ?? 0;

            $read->fullRead("SELECT COUNT(id) as total_partidas FROM adms_partidas WHERE status_partida = 'Finalizado'");
            $this->result['contagem']['partidas'] = $read->getResult()[0]['total_partidas'] ?? 0;

            $read->fullRead("SELECT id, name AS nome, apelido, imagem, pontuacao_ranking 
                             FROM adms_users 
                             WHERE adms_access_level_id = 14 
                             ORDER BY pontuacao_ranking DESC LIMIT 1");
            $this->result['lider'] = $read->getResult()[0] ?? null;

        } else {
            // =========================================================
            // VISÃO DO CLUBE - Vê Apenas o seu Ecossistema
            // =========================================================
            $queryAtletas = "SELECT COUNT(DISTINCT usr.id) as total_atletas 
                             FROM adms_users AS usr
                             LEFT JOIN adms_inscricoes AS ins ON ins.adms_user_id = usr.id
                             LEFT JOIN adms_competicoes AS comp ON comp.id = ins.adms_competicao_id
                             WHERE usr.adms_access_level_id = 14 
                             AND (
                                 usr.clube_filiacao_id = :empresa 
                                 OR (comp.empresa_id = :empresa AND ins.status_pagamento_id = 2)
                             )";
            $read->fullRead($queryAtletas, "empresa={$empresaId}");
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

            $queryLider = "SELECT DISTINCT usr.id, usr.name AS nome, usr.apelido, usr.imagem, usr.pontuacao_ranking 
                           FROM adms_users AS usr
                           LEFT JOIN adms_inscricoes AS ins ON ins.adms_user_id = usr.id
                           LEFT JOIN adms_competicoes AS comp ON comp.id = ins.adms_competicao_id
                           WHERE usr.adms_access_level_id = 14 
                           AND (
                               usr.clube_filiacao_id = :empresa 
                               OR (comp.empresa_id = :empresa AND ins.status_pagamento_id = 2)
                           )
                           ORDER BY usr.pontuacao_ranking DESC LIMIT 1";
            $read->fullRead($queryLider, "empresa={$empresaId}");
            $this->result['lider'] = $read->getResult()[0] ?? null;
        }
    }

    // ========================================================================
    // ESTATÍSTICAS DO ÁRBITRO
    // ========================================================================
    public function getEstatisticasArbitro(int $arbitroId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead(
            "SELECT COUNT(id) as total_apitos FROM adms_partidas 
             WHERE arbitro_id = :arbitro_id AND status_partida = 'Finalizado'",
            "arbitro_id={$arbitroId}"
        );
        $this->result['arbitro_stats']['total_apitos'] = $read->getResult()[0]['total_apitos'] ?? 0;

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

// ========================================================================
    // DOCAN ENGINE: VITRINE DE COMPETIÇÕES PARA ATLETAS (COM LOGO E STATUS)
    // ========================================================================
    public function getVitrineCompeticoes(int $userId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca todos os torneios ativos
        $read->fullRead(
            "SELECT c.id, c.nome_torneio, c.data_evento, c.local_evento, c.categoria_cbtm, 
                    emp.nome_fantasia as clube_nome, emp.logo as clube_logo, emp.id as clube_id
             FROM adms_competicoes c
             LEFT JOIN adms_emp_principal emp ON emp.id = c.empresa_id
             WHERE c.status_inscricao = 1
             ORDER BY c.data_evento ASC"
        );
        $torneios = $read->getResult() ?: [];

        // 2. Busca em quais torneios este atleta já está inscrito
        $read->fullRead(
            "SELECT adms_competicao_id, status_pagamento_id 
             FROM adms_inscricoes 
             WHERE adms_user_id = :user_id", 
            "user_id={$userId}"
        );
        $inscricoes = $read->getResult() ?: [];
        
        $statusPorTorneio = [];
        foreach ($inscricoes as $insc) {
            $compId = $insc['adms_competicao_id'];
            $statusPorTorneio[$compId] = $insc['status_pagamento_id'] ?? 1; 
        }

        // 3. Junta as informações: Marca quem já está inscrito e o status de pagamento
        foreach ($torneios as $key => $t) {
            if (isset($statusPorTorneio[$t['id']])) {
                $torneios[$key]['ja_inscrito'] = true;
                $torneios[$key]['status_pagamento'] = $statusPorTorneio[$t['id']];
            } else {
                $torneios[$key]['ja_inscrito'] = false;
                $torneios[$key]['status_pagamento'] = 1;
            }
        }

        $this->result['vitrine'] = $torneios;
    }
}