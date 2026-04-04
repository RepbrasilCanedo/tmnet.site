<?php
namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsListEmpresas
{
    // Inicializamos com valores padrão para evitar o erro de "not initialized"
    private bool $result = false;
    private array|null $resultBd = null;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg = null;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }
    function getResultPg(): string|null { return $this->resultPg; }

    public function listEmpresas(int $page, array $filters = []): void
    {
        $this->page = $page > 0 ? $page : 1;
        $userLevel = $_SESSION['adms_access_level_id'];
        $empUser = $_SESSION['emp_user'];
        $userId = $_SESSION['user_id'];

        $conditions = [];
        $params = [];

        // 1. Filtros de Hierarquia
        if ($userLevel > 2) {
            $conditions[] = "clie.empresa = :empresa_id";
            $params['empresa_id'] = $empUser;

            if ($userLevel == 12) {
                $checkVinc = new \App\adms\Models\helper\AdmsRead();
                $checkVinc->fullRead("SELECT id FROM adms_user_clie WHERE user_id = :u AND empresa_id = :e LIMIT 1", "u={$userId}&e={$empUser}");
                if ($checkVinc->getResult()) {
                    $conditions[] = "clie.id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = :u_id)";
                    $params['u_id'] = $userId;
                }
            }
        }

        // 2. Filtros de Busca (Search)
        if (!empty($filters['search_cnpj'])) {
            // Removemos a máscara para buscar apenas os números no banco
            $cnpjLimpo = preg_replace('/\D/', '', $filters['search_cnpj']);
            $conditions[] = "clie.cnpjcpf LIKE :cnpj";
            $params['cnpj'] = "%" . $cnpjLimpo . "%";
        }
        if (!empty($filters['search_razao'])) {
            $conditions[] = "clie.razao_social LIKE :razao";
            $params['razao'] = "%" . trim($filters['search_razao']) . "%";
        }
        if (!empty($filters['search_fantasia'])) {
            $conditions[] = "clie.nome_fantasia LIKE :fantasia";
            $params['fantasia'] = "%" . trim($filters['search_fantasia']) . "%";
        }

        $where = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";

        // 3. Paginação
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-empresas/index', http_build_query($filters));
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(clie.id) AS num_result FROM adms_clientes AS clie $where", http_build_query($params));
        $this->resultPg = $pagination->getResult();
        
        // Contagem para o topo
        $readTotal = new \App\adms\Models\helper\AdmsRead();
        $readTotal->fullRead("SELECT COUNT(clie.id) as total FROM adms_clientes clie $where", http_build_query($params));
        $resTotal = $readTotal->getResult();
        $_SESSION['resultado'] = $resTotal[0]['total'] ?? 0;

        // 4. Busca de Dados
        if ($this->resultPg) {
            $list = new \App\adms\Models\helper\AdmsRead();
            $query = "SELECT clie.id, clie.razao_social, clie.nome_fantasia, clie.cnpjcpf, clie.bairro, clie.cidade, clie.uf, sit.name as name_sit
                      FROM adms_clientes AS clie
                      INNER JOIN adms_sits_empr_unid AS sit ON sit.id = clie.situacao 
                      $where 
                      ORDER BY clie.nome_fantasia  
                      LIMIT :limit OFFSET :offset";

            $params['limit'] = $this->limitResult;
            $params['offset'] = $pagination->getOffset();

            $list->fullRead($query, http_build_query($params));
            $this->resultBd = $list->getResult();
            
            if($this->resultBd){
                $this->result = true;
            } else {
                $this->result = false;
            }
        } else {
            $this->result = false;
            $this->resultBd = []; // Inicializamos como array vazio caso não haja páginas
        }
    }
}