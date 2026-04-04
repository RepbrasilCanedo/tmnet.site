<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListProd
{
    private bool $result = false;
    private array|null $resultBd;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;
    private int $totalGeral = 0;
    private int $totalVencidos = 0;
    private int $totalAVencer = 0;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }
    function getResultPg(): string|null { return $this->resultPg; }
    function getTotalGeral(): int { return $this->totalGeral; }
    function getTotalVencidos(): int { return $this->totalVencidos; }
    function getTotalAVencer(): int { return $this->totalAVencer; }

    public function listProd($page): void
    {
        $this->listSearchProd($page, null, null, null, null, null, null);
    }

    public function listSearchProd($page, ?string $search_tipo, ?string $search_emp, ?string $search_prod, ?string $date_start, ?string $date_end, ?string $search_sit): void
    {
        $this->page = (int)$page ?: 1;
        $empId = (int)$_SESSION['emp_user'];
        $userId = (int)$_SESSION['user_id'];
        $userLevel = (int)$_SESSION['adms_access_level_id'];

        // Alterado: O INNER JOIN no 'adms_contr' foi substituído por um LEFT JOIN na 'adms_contrato' focado no cliente
        $query_base = " FROM adms_produtos AS prod 
                        INNER JOIN adms_type_equip AS typ ON typ.id = prod.type_id 
                        INNER JOIN adms_clientes AS clie ON clie.id = prod.cliente_id 
                        INNER JOIN adms_sit_equip AS sit ON sit.id = prod.sit_id
                        LEFT JOIN adms_contrato AS cont ON cont.cliente_id = prod.cliente_id AND cont.status = 1
                        WHERE prod.empresa_id = :empresa_id";

        $bindValues = "empresa_id={$empId}";

        // Segurança de Vínculos
        if ($userLevel == 12) {
            $readV = new \App\adms\Models\helper\AdmsRead();
            $readV->fullRead("SELECT id FROM adms_user_clie WHERE user_id = $userId LIMIT 1");
            if ($readV->getResult()) {
                $query_base .= " AND prod.cliente_id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = $userId)";
            } else {
                $query_base .= " AND prod.cliente_id NOT IN (SELECT cliente_id FROM adms_user_clie)";
            }
        }

        // Filtros Dinâmicos (ajustado o filtro de datas para a nova coluna 'final_contr')
        if (!empty($search_tipo)) { $query_base .= " AND prod.type_id = :search_tipo"; $bindValues .= "&search_tipo={$search_tipo}"; }
        if (!empty($search_prod)) { $query_base .= " AND prod.name LIKE :search_prod"; $bindValues .= "&search_prod=%{$search_prod}%"; }
        if (!empty($search_emp))  { $query_base .= " AND prod.cliente_id = :search_emp"; $bindValues .= "&search_emp={$search_emp}"; }
        if (!empty($search_sit))  { $query_base .= " AND prod.sit_id = :search_sit"; $bindValues .= "&search_sit={$search_sit}"; }
        if (!empty($date_start) && !empty($date_end)) { 
            $query_base .= " AND cont.final_contr BETWEEN :date_start AND :date_end"; 
            $bindValues .= "&date_start={$date_start}&date_end={$date_end}"; 
        }

        $readTotal = new \App\adms\Models\helper\AdmsRead();
        
        // Contadores
        $readTotal->fullRead("SELECT COUNT(prod.id) AS total" . $query_base, $bindValues);
        $this->totalGeral = (int)($readTotal->getResult()[0]['total'] ?? 0);

        // Vencidos: Olha para cont.final_contr garantindo que não é 'Indeterminado' (nulo ou 0000)
        $readTotal->fullRead("SELECT COUNT(prod.id) AS total" . $query_base . " AND cont.final_contr < CURDATE() AND cont.final_contr != '0000-00-00' AND cont.final_contr IS NOT NULL", $bindValues);
        $this->totalVencidos = (int)($readTotal->getResult()[0]['total'] ?? 0);

        // A Vencer: Mesma lógica
        $readTotal->fullRead("SELECT COUNT(prod.id) AS total" . $query_base . " AND cont.final_contr BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)", $bindValues);
        $this->totalAVencer = (int)($readTotal->getResult()[0]['total'] ?? 0);

        // Paginação
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-prod/index', $bindValues);
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(prod.id) AS num_result" . $query_base, $bindValues);
        $this->resultPg = $pagination->getResult();

        // Query Final: Busca as colunas do contrato atual
        $readTotal->fullRead("SELECT prod.id, prod.name, typ.name as name_type, clie.nome_fantasia as nome_fantasia_clie, cont.name AS name_contr, cont.final_contr, sit.name as name_sit" 
                            . $query_base . " ORDER BY prod.id DESC LIMIT :limit OFFSET :offset", 
                            "{$bindValues}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
        $this->resultBd = $readTotal->getResult();
        $this->result = (bool)$this->resultBd;
    }

    public function listSelect(): array {
        $list = new \App\adms\Models\helper\AdmsRead();
        $empId = $_SESSION['emp_user'];
        $userId = $_SESSION['user_id'];

        $whereClie = ($_SESSION['adms_access_level_id'] == 12) ? "AND id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = $userId)" : "";
        $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes WHERE empresa = $empId $whereClie ORDER BY nome_fantasia");
        $registry['nome_clie'] = $list->getResult();

        $list->fullRead("SELECT id, name FROM adms_type_equip WHERE empresa_id = $empId ORDER BY name");
        $registry['tipo_equip'] = $list->getResult();

        $list->fullRead("SELECT id, name FROM adms_sit_equip ORDER BY name");
        $registry['sit_equip'] = $list->getResult();

        return $registry;
    }
}