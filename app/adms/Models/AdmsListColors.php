<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar cores do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListColors
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

    /** @var string|null $searchName Recebe o nome da cor */
    private string|null $searchName;

    /** @var string|null $searchEmail Recebe o nome da cor em hexadecimal */
    private string|null $searchColor;

    /** @var string|null $searchName Recebe o nome da cor */
    private string|null $searchNameValue;

    /** @var string|null $searchEmail Recebe o nome da cor em hexadecimal */
    private string|null $searchColorValue;

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
     * Metodo faz a pesquisa das cores na tabela "adms_colors" e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * @param integer|null $page
     * @return void
     */
    public function listColors(int $page):void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(col.id) AS num_result FROM adms_colors AS col");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT id, name, color 
                            FROM adms_colors
                            ORDER BY id DESC
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();        
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa das cores na tabela adms_colors e lista as informacoes na view
     * Recebe o paramentro "page" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_name" para que seja feita a pesquisa pelo nome da cor
     * Recebe o paramentro "search_color" para que seja feita a pesquisa pelo nome em hexadecimal
     * @param integer|null $page
     * @param string|null $search_name
     * @param string|null $search_color
     * @return void
     */
    public function listSearchColors(int $page, string|null $search_name, string|null $search_color): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchName = trim($search_name);
        $this->searchColor = trim($search_color);

        $this->searchNameValue = "%" . $this->searchName . "%";
        $this->searchColorValue = "%" . $this->searchColor . "%";

        if ((!empty($this->searchName)) and (!empty($this->searchColor))) {
            $this->searchColorsNameCol();
        } elseif ((!empty($this->searchName)) and (empty($this->searchColor))) {
            $this->searchColorsName();
        } elseif ((empty($this->searchName)) and (!empty($this->searchColor))) {
            $this->searchColorsCol();
        } else {
            $this->searchColorsNameCol();
        }
    }

    /**
     * Metodo pesquisar pelo nome da cor e cor em hexadecimal
     * @return void
     */
    public function searchColorsNameCol(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index', "?search_name={$this->searchName}&search_color={$this->searchColor}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(col.id) AS num_result 
                                FROM adms_colors AS col
                                WHERE col.name LIKE :search_name
                                OR col.color LIKE :search_color", "search_name={$this->searchNameValue}&search_color={$this->searchColorValue}");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT col.id, col.name, col.color
                    FROM adms_colors AS col 
                    WHERE col.name LIKE :search_name
                    OR col.color LIKE :search_color
                    ORDER BY col.id DESC
                    LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&search_color={$this->searchColorValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo nome
     * @return void
     */
    public function searchColorsName(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index', "?search_name={$this->searchName}&search_color={$this->searchColor}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(col.id) AS num_result 
                                FROM adms_colors AS col
                                WHERE col.name LIKE :search_name", "search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT col.id, col.name, col.color
                            FROM adms_colors AS col 
                            WHERE col.name LIKE :search_name
                            ORDER BY col.id DESC
                            LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pela cor em hexadecimal
     * @return void
     */
    public function searchColorsCol(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index', "?search_name={$this->searchName}&search_color={$this->searchColor}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(col.id) AS num_result 
                                FROM adms_colors AS col
                                WHERE col.color LIKE :search_color", "search_color={$this->searchColorValue}");
        $this->resultPg = $pagination->getResult();

        $listColors = new \App\adms\Models\helper\AdmsRead();
        $listColors->fullRead("SELECT col.id, col.name, col.color
                            FROM adms_colors AS col 
                            WHERE col.color LIKE :search_color
                            ORDER BY col.id DESC
                            LIMIT :limit OFFSET :offset", "search_color={$this->searchColorValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listColors->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma cor encontrada!</p>";
            $this->result = false;
        }
    }

    
}
