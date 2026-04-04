<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsPerfilAtleta
{
    private array|null $dadosPerfil;
    private array|null $historico;
    private array|null $proximosJogos = [];
    private array $estatisticas = [
        'total_jogos' => 0,
        'vitorias' => 0,
        'derrotas' => 0,
        'aproveitamento' => 0
    ];

    function getDadosPerfil(): array|null { return $this->dadosPerfil; }
    function getHistorico(): array|null { return $this->historico; }
    function getProximosJogos(): array|null { return $this->proximosJogos; }
    function getEstatisticas(): array { return $this->estatisticas; }

    public function carregarPerfil(int $id): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca os dados do atleta (Adicionei mao_dominante para o perfil ficar mais rico)
        $read->fullRead(
            "SELECT id, name, apelido, imagem, pontuacao_ranking, estilo_jogo, mao_dominante, created 
             FROM adms_users 
             WHERE id = :id AND empresa_id = :empresa LIMIT 1",
            "id={$id}&empresa={$_SESSION['emp_user']}"
        );

        if ($read->getResult()) {
            $this->dadosPerfil = $read->getResult()[0];
            $this->carregarHistorico($id);
            $this->carregarProximosJogos($id);
        } else {
            $this->dadosPerfil = null;
        }
    }

    private function carregarProximosJogos(int $idAtleta): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // Busca os jogos com status "Agendado"
        $read->fullRead(
            "SELECT p.id, p.fase, p.mesa, p.horario_previsto, 
                    p.atleta_a_id, p.atleta_b_id,
                    ua.name as nome_a, ub.name as nome_b,
                    c.nome_torneio, c.local_evento, c.data_evento, d.nome as div_nome
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             LEFT JOIN adms_divisoes d ON d.id = p.adms_divisao_id
             WHERE (p.atleta_a_id = :id OR p.atleta_b_id = :id) 
               AND p.status_partida = 'Agendado'
             ORDER BY c.data_evento ASC, p.horario_previsto ASC",
            "id={$idAtleta}"
        );

        $this->proximosJogos = $read->getResult();
    }

    private function carregarHistorico(int $idAtleta): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead(
            "SELECT p.id, p.fase, p.vencedor_id, p.sets_atleta_a, p.sets_atleta_b, 
                    p.atleta_a_id, p.atleta_b_id, p.pontos_ganhos,
                    ua.name as nome_a, ub.name as nome_b,
                    c.nome_torneio, c.data_evento
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             INNER JOIN adms_users ua ON ua.id = p.atleta_a_id
             INNER JOIN adms_users ub ON ub.id = p.atleta_b_id
             WHERE (p.atleta_a_id = :id OR p.atleta_b_id = :id) 
               AND p.status_partida = 'Finalizado'
             ORDER BY p.id DESC LIMIT 15", // Limitamos aos últimos 15 jogos para não pesar o telemóvel
            "id={$idAtleta}"
        );

        $this->historico = $read->getResult();

        if ($this->historico) {
            $this->estatisticas['total_jogos'] = count($this->historico); // Estatística baseada nos últimos 15 jogos
            
            foreach ($this->historico as $jogo) {
                if ($jogo['vencedor_id'] == $idAtleta) {
                    $this->estatisticas['vitorias']++;
                } else {
                    $this->estatisticas['derrotas']++;
                }
            }

            $calc = ($this->estatisticas['vitorias'] / $this->estatisticas['total_jogos']) * 100;
            $this->estatisticas['aproveitamento'] = round($calc, 1);
        }
    }
}