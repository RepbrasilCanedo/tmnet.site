<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar tipos de páginas do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListTypesPages
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

    /** @var string|null $searchName Recebe o nome do tipo de página */
    private string|null $searchType;

    /** @var string|null $searchNameValue Recebe o nome do tipo de página */
    private string|null $searchTypeValue;

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
     * Metodo faz a pesquisa dos grupos de páginas na tabela adms_types_pgs e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listTypesPages(int $page = null):void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-types-pages/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_types_pgs");
        $this->resultPg = $pagination->getResult();

        $listTypesPages = new \App\adms\Models\helper\AdmsRead();
        $listTypesPages->fullRead("SELECT id, type, name, order_type_pg 
                            FROM adms_types_pgs
                            ORDER BY order_type_pg ASC
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listTypesPages->getResult();
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum tipo de página encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos tipos de páginas na tabela adms_types_pgs e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * Recebe o paramentro "search_type" para pesquisar o tipo de página atraves do campo "type"
     * @param integer|null $page
     * @param string|null $search_type
     * @return void
     */
    public function listSearchTypesPages(int $page = null, string|null $search_type):void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchType = trim($search_type);

        $this->searchTypeValue = "%" . $this->searchType . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-types-pages/index', "?search_type={$this->searchType}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_types_pgs
                                WHERE type LIKE :search_type
                                OR name LIKE :search_type", "search_type={$this->searchTypeValue}");
        $this->resultPg = $pagination->getResult();

        $listTypesPages = new \App\adms\Models\helper\AdmsRead();
        $listTypesPages->fullRead("SELECT id, type, name, order_type_pg 
                            FROM adms_types_pgs
                            WHERE type LIKE :search_type
                            OR name LIKE :search_type 
                            ORDER BY id DESC
                            LIMIT :limit OFFSET :offset", "search_type={$this->searchTypeValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listTypesPages->getResult();        
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum tipo de página encontrado!</p>";
            $this->result = false;
        }
    }
    
}
