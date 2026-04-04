<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar nivel de acesso do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListAccessLevels
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

    /** @var string|null $searchName Recebe o nome do nivel de acesso */
    private string|null $searchName;

    /** @var string|null $searchNameValue Recebe o nome do nivel de acesso */
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
     * Metodo faz a pesquisa dos niveis de acesso na tabela "adms_access_levels" e lista as informacoes na view
     * Recebe como parametro "page" para fazer a paginacao
     * @param integer|null $page
     * @return void
     */
    public function listAccessLevels(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-access-levels/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_access_levels
                                WHERE order_levels >=:order_levels", "order_levels=" . $_SESSION['order_levels']);
        $this->resultPg = $pagination->getResult();

        $listAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $listAccessLevels->fullRead("SELECT id, name, order_levels 
                            FROM adms_access_levels
                            WHERE order_levels >=:order_levels
                            ORDER BY order_levels ASC
                            LIMIT :limit OFFSET :offset", "order_levels=" . $_SESSION['order_levels'] . "&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listAccessLevels->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum nível de acesso encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos niveis de acesso na tabela adms_access_levels e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * Recebe o paramentro "search_name" para pesquisar a nivel de acesso atraves do nome
     * @param integer|null $page
     * @param string|null $search_name
     * @return void
     */
    public function listSearchAccessLevels(int $page = null, string|null $search_name):void
    {
        $this->page = (int) $page ? $page : 1;
        $this->searchName = trim($search_name);

        $this->searchNameValue = "%" . $this->searchName . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-access-levels/index', "?search_name={$this->searchName}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_access_levels
                                WHERE name LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listSearchAccessLevels = new \App\adms\Models\helper\AdmsRead();
        $listSearchAccessLevels->fullRead("SELECT id, name, order_levels
                            FROM adms_access_levels
                            WHERE name LIKE :search_name 
                            ORDER BY id DESC
                            LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listSearchAccessLevels->getResult();        
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum nível de acesso encontrado!</p>";
            $this->result = false;
        }
    }
}
