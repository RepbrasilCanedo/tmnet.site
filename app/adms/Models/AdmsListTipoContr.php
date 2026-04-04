<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar tipos de contratos do banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListTipoContr
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
     * Lista os tipos de contratos com paginação
     */
    public function listTipoContr(int $page): void
    {
        $this->page = $page ?: 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-tipo-contr/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_tipo_contrato WHERE empresa_id= :empresa_id", "empresa_id={$_SESSION['emp_user']}");
        $this->resultPg = $pagination->getResult();

        $listQuery = new \App\adms\Models\helper\AdmsRead();
        $listQuery->fullRead("SELECT type.id, type.name, type.empresa_id, type.status, sit.name as name_sit 
                              FROM adms_tipo_contrato as type
                              INNER JOIN adms_contr_sit AS sit ON sit.id=type.status 
                              WHERE type.empresa_id= :empresa_id
                              LIMIT :limit OFFSET :offset", 
                              "empresa_id={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listQuery->getResult();        
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum Tipo de Contrato encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Pesquisa os tipos de contratos pelo nome
     */
    public function listSearchCTipoContr(int $page, string $searchName): void
    {
        $this->page = $page ?: 1;
        $this->searchName = trim($searchName);
        $this->searchNameValue = "%" . $this->searchName . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-tipo-contr/index', "?search_name={$this->searchName}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_tipo_contrato WHERE empresa_id= :empresa_id AND name LIKE :search_name", "empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}");
      
        $this->resultPg = $pagination->getResult();

        $listQuery = new \App\adms\Models\helper\AdmsRead();
        $listQuery->fullRead("SELECT type.id, type.name, type.empresa_id, type.status, sit.name as name_sit 
                              FROM adms_tipo_contrato as type
                              INNER JOIN adms_contr_sit AS sit ON sit.id=type.status 
                              WHERE type.empresa_id= :empresa_id AND type.name LIKE :search_name
                              LIMIT :limit OFFSET :offset", 
                              "empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");       
        
        $this->resultBd = $listQuery->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-warning'>Nenhum tipo de contrato encontrado para esta pesquisa!</p>";
            $this->result = false;
        }
    }
}