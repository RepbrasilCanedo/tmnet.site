<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar grupos de páginas do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListGroupsPages
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int $page Recebe o número página */
    private int $page;

    /** @var int $page Recebe a quantidade de registros que deve retornar do banco de dados */
    private int $limitResult = 40;

    /** @var string|null $page Recebe a páginação */
    private string|null $resultPg;

    /** @var string|null $searchName Recebe o nome do grupo de página */
    private string|null $searchName;

    /** @var string|null $searchNameValue Recebe o nome do grupo de página */
    private string|null $searchNameValue;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os registros do BD
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * @return bool Retorna a paginação
     */
    function getResultPg(): string|null
    {
        return $this->resultPg;
    }

    /**
     * Metodo faz a pesquisa dos grupos de páginas na tabela adms_groups_pgs e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listGroupsPages(int $page = null):void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-groups-pages/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_groups_pgs");
        $this->resultPg = $pagination->getResult();

        $listGroupsPages = new \App\adms\Models\helper\AdmsRead();
        $listGroupsPages->fullRead("SELECT id, name, order_group_pg 
                            FROM adms_groups_pgs
                            ORDER BY order_group_pg ASC
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listGroupsPages->getResult();
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum grupo de página encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos grupos de página na tabela adms_groups_pgs e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * Recebe o paramentro "search_name" para pesquisar a grupo de página atraves do nome
     * @param integer|null $page
     * @param string|null $search_name
     * @return void
     */
    public function listSearchGroupsPages(int $page = null, string|null $search_name):void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchName = trim($search_name);

        $this->searchNameValue = "%" . $this->searchName . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-groups-pages/index', "?search_name={$this->searchName}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_groups_pgs
                                WHERE name LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listGroupsPages = new \App\adms\Models\helper\AdmsRead();
        $listGroupsPages->fullRead("SELECT id, name, order_group_pg 
                            FROM adms_groups_pgs
                            WHERE name LIKE :search_name 
                            ORDER BY id DESC
                            LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listGroupsPages->getResult();        
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum grupo de página encontrado!</p>";
            $this->result = false;
        }
    }

    
}
