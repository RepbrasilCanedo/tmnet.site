<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar contratos do banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListContratos
{
    private bool $result = false;
    private array|null $resultBd = null;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg = null;
    private string|null $searchName = null;
    private string|null $searchNameValue = null;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    /**
     * Lista os contratos com paginação
     */
    public function listContratos(int $page): void
    {
        $this->page = $page ?: 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-contratos/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_contrato WHERE empresa_id = :empresa_id", "empresa_id={$_SESSION['emp_user']}");
        $this->resultPg = $pagination->getResult();

        $listQuery = new \App\adms\Models\helper\AdmsRead();
        // Utiliza LEFT JOIN para garantir que o contrato aparece mesmo que o tipo ou status tenham sido apagados
        $listQuery->fullRead(
            "SELECT c.id, c.name, c.inicio_contr, c.final_contr, cli.nome_fantasia as nome_fantasia_cli, tc.name AS tipo_nome, sit.name AS sit_nome 
             FROM adms_contrato AS c
             LEFT JOIN adms_clientes AS cli ON cli.id = c.cliente_id
             LEFT JOIN adms_tipo_contrato AS tc ON tc.id = c.tipo_contr 
             LEFT JOIN adms_contr_sit AS sit ON sit.id = c.status 
             WHERE c.empresa_id = :empresa_id
             ORDER BY c.id DESC
             LIMIT :limit OFFSET :offset", 
            "empresa_id={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}"
        );

        $this->resultBd = $listQuery->getResult();        
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-warning'>Aviso: Nenhum Contrato encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Pesquisa os contratos pelo nome
     */
    public function listSearchContratos(int $page, string $searchName): void
    {
        $this->page = $page ?: 1;
        $this->searchName = trim($searchName);
        $this->searchNameValue = "%" . $this->searchName . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-contratos/index', "?search_name={$this->searchName}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_contrato WHERE empresa_id = :empresa_id AND name LIKE :search_name", "empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}");
      
        $this->resultPg = $pagination->getResult();

        $listQuery = new \App\adms\Models\helper\AdmsRead();
        $listQuery->fullRead(
            "SELECT c.id, c.name, c.inicio_contr, c.final_contr, cli.nome_fantasia as nome_fantasia_cli, tc.name AS tipo_nome, sit.name AS sit_nome 
             FROM adms_contrato AS c
             LEFT JOIN adms_tipo_contrato AS tc ON tc.id = c.tipo_contr 
             LEFT JOIN adms_clientes AS cli ON cli.id = c.cliente_id
             LEFT JOIN adms_contr_sit AS sit ON sit.id = c.status 
             WHERE c.empresa_id = :empresa_id AND c.name LIKE :search_name
             ORDER BY c.id DESC
             LIMIT :limit OFFSET :offset", 
            "empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}"
        );       
        
        $this->resultBd = $listQuery->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-warning'>Nenhum contrato encontrado para esta pesquisa!</p>";
            $this->result = false;
        }
    }
}