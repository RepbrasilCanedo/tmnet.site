<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListCompeticoes
{
    private array|null $result;

    function getResult(): array|null
    {
        return $this->result;
    }

    /**
     * @param array|null $search Dados vindos do formulário de busca
     */
    public function list(array|null $search = null): void
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        
        $filtroQuery = "";
        $filtroParams = "empresa={$_SESSION['emp_user']}";

        if (!empty($search['search_nome'])) {
            $filtroQuery = "AND c.nome_torneio LIKE :nome";
            $filtroParams .= "&nome=%{$search['search_nome']}%";
        }

        // Busca Inteligente: Traz os dados do torneio + status de inscrições + progresso dos jogos
        $list->fullRead(
            "SELECT c.id, c.nome_torneio, c.data_evento, c.local_evento, c.categoria_cbtm, c.fator_multiplicador, c.status_inscricao,
                    (SELECT COUNT(id) FROM adms_partidas WHERE adms_competicao_id = c.id) as total_partidas,
                    (SELECT COUNT(id) FROM adms_partidas WHERE adms_competicao_id = c.id AND vencedor_id IS NOT NULL AND vencedor_id > 0) as partidas_concluidas
             FROM adms_competicoes c
             WHERE c.empresa_id = :empresa {$filtroQuery}
             ORDER BY c.data_evento DESC", 
            $filtroParams
        );

        $torneios = $list->getResult() ?: [];

        // Trata o Status do Torneio no PHP antes de enviar para a View
        foreach ($torneios as $key => $t) {
            $statusTorneio = "Aguardando";
            
            if ($t['total_partidas'] > 0) {
                if ($t['total_partidas'] == $t['partidas_concluidas']) {
                    $statusTorneio = "Concluído";
                } else {
                    $statusTorneio = "Em Andamento";
                }
            }
            $torneios[$key]['status_torneio'] = $statusTorneio;
        }

        $this->result = $torneios;
    }
}