<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsExportTicketSla
{
    private $resultBd;

    public function getResultBd() { return $this->resultBd; }

    public function exportSla(?array $filter, string $modo): void
    {
        $empId = (int) $_SESSION['emp_user'];
        $where = " WHERE sla_hist.empresa_id = :empresa_id";
        $params = "empresa_id={$empId}";

        if($modo == 'resposta'){
            $where .= " AND sla_hist.status_id_ant IN (2, 9)";
        }

        if ($filter) {
            if (!empty($filter['search_ticket'])) { $where .= " AND sla_hist.id_ticket = :tk"; $params .= "&tk={$filter['search_ticket']}"; }
            if (!empty($filter['search_suporte'])) { $where .= " AND sla_hist.suporte_id = :sup"; $params .= "&sup={$filter['search_suporte']}"; }
            if (!empty($filter['search_date_start'])) { $where .= " AND sla_hist.dt_status >= :d_st"; $params .= "&d_st={$filter['search_date_start']} 00:00:00"; }
            if (!empty($filter['search_date_end'])) { $where .= " AND sla_hist.dt_status <= :d_en"; $params .= "&d_en={$filter['search_date_end']} 23:59:59"; }
        }

        $metaCol = ($modo == 'resposta') ? "sla.prim_resp" : "sla_hist.tempo_sla_fin";

        $query = "SELECT sla_hist.id_ticket AS id_ticket_sla_hist, sla_hist.dt_abert_ticket, 
                         sla_hist.dt_status, sla_hist.tempo_sla AS tempo_gasto, 
                         $metaCol AS tempo_meta, clie.nome_fantasia AS nome_fantasia_clie, 
                         sta_ant.name AS name_status_id_ant, sta_atu.name AS name_sta_atu, 
                         user.name AS name_user
                  FROM adms_sla_hist AS sla_hist
                  INNER JOIN adms_clientes AS clie ON clie.id = sla_hist.cliente_id 
                  INNER JOIN adms_sla AS sla ON sla.id = sla_hist.id_sla 
                  INNER JOIN adms_cham_status AS sta_ant ON sta_ant.id = sla_hist.status_id_ant
                  INNER JOIN adms_cham_status AS sta_atu ON sta_atu.id = sla_hist.status_id
                  INNER JOIN adms_users AS user ON user.id = sla_hist.suporte_id 
                  $where ORDER BY sla_hist.dt_status DESC";

        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead($query, $params);
        $this->resultBd = $read->getResult();
    }
}