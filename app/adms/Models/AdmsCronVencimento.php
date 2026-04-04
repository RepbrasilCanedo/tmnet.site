<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsCronVencimento
{
    /**
     * Busca equipamentos ativos que vencem nos próximos 7 dias
     */
    public function buscarVencimentosSemana(): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        // Filtra: Ativos (sit_id = 1) e Vencimento entre hoje e +7 dias
        $query = "SELECT prod.name, clie.nome_fantasia, prod.venc_contr 
                  FROM adms_produtos AS prod
                  INNER JOIN adms_clientes AS clie ON clie.id = prod.cliente_id
                  WHERE prod.venc_contr BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                  AND prod.sit_id = 1
                  ORDER BY prod.venc_contr ASC";
        
        $read->fullRead($query);
        return $read->getResult();
    }
}