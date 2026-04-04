<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar páginas do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListPages
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

    /** @var string|null $searchName Recebe o controller */
    private string|null $searchController;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchMetodo;

    /** @var string|null $searchName Recebe o controller */
    private string|null $searchControllerValue;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchMetodoValue;

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
     * Metodo faz a pesquisa das páginas na tabela adms_pages e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listPages(int $page):void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-pages/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_pages");
        $this->resultPg = $pagination->getResult();

        $listPages = new \App\adms\Models\helper\AdmsRead();
        $listPages->fullRead("SELECT pg.id, pg.controller, pg.menu_controller, pg.name_page, pg.obs,
                        tpg.type type_tpg, tpg.name name_tpg,
                        sit.name name_sit,
                        col.color 
                        FROM adms_pages AS pg
                        LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                        LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                        LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        ORDER BY id DESC
                        LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listPages->getResult();
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa das páginas na tabela adms_pages e lista as informacoes na view
     * Recebe o paramentro "page" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_controller" para que seja feita a pesquisa pelo controller
     * Recebe o paramentro "search_metodo" para que seja feita a pesquisa pelo metodo
     * @param integer|null $page
     * @param string|null $search_controller
     * @param string|null $search_metodo
     * @return void
     */
    public function listSearchPages(int $page = null, string|null $search_controller, string|null $search_metodo): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchController = trim($search_controller);
        $this->searchMetodo = trim($search_metodo);

        $this->searchControllerValue = "%" . $this->searchController . "%";
        $this->searchMetodoValue = "%" . $this->searchMetodo . "%";

        if ((!empty($this->searchController)) and (!empty($this->searchMetodo))) {
            $this->searchPagesControllerMetodo();
        } elseif ((!empty($this->searchController)) and (empty($this->searchMetodo))) {
            $this->searchPagesController();
        } elseif ((empty($this->searchController)) and (!empty($this->searchMetodo))) {
            $this->searchPagesMetodo();
        } else {
            $this->searchPagesControllerMetodo();
        }
    }

    /**
     * Metodo pesquisar pelo controller e metodo
     * @return void
     */
    public function searchPagesControllerMetodo(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-pages/index', "?search_controller={$this->searchController}&search_metodo={$this->searchMetodo}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_pages
                                WHERE controller LIKE :search_controller 
                                OR metodo LIKE :search_metodo", "search_controller={$this->searchControllerValue}&search_metodo={$this->searchMetodoValue}");                        
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT pg.id, pg.name_page,
                    tpg.type type_tpg, tpg.name name_tpg,
                    sit.name name_sit,
                    col.color 
                    FROM adms_pages AS pg
                    LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                    LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                    LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id 
                    WHERE controller LIKE :search_controller 
                    OR metodo LIKE :search_metodo
                    ORDER BY pg.id DESC
                    LIMIT :limit OFFSET :offset", "search_controller={$this->searchControllerValue}&search_metodo={$this->searchMetodoValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo controller
     * @return void
     */
    public function searchPagesController(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-pages/index', "?search_controller={$this->searchController}&search_metodo={$this->searchMetodo}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_pages
                                WHERE controller LIKE :search_controller", "search_controller={$this->searchControllerValue}");                        
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT pg.id, pg.name_page,
                    tpg.type type_tpg, tpg.name name_tpg,
                    sit.name name_sit,
                    col.color 
                    FROM adms_pages AS pg
                    LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                    LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                    LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id 
                    WHERE controller LIKE :search_controller
                    ORDER BY pg.id DESC
                    LIMIT :limit OFFSET :offset", "search_controller={$this->searchControllerValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo metodo
     * @return void
     */
    public function searchPagesMetodo(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-pages/index', "?search_controller={$this->searchController}&search_metodo={$this->searchMetodo}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_pages
                                WHERE metodo LIKE :search_metodo", "search_metodo={$this->searchMetodoValue}");                        
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT pg.id, pg.name_page,
                    tpg.type type_tpg, tpg.name name_tpg,
                    sit.name name_sit,
                    col.color 
                    FROM adms_pages AS pg
                    LEFT JOIN adms_types_pgs AS tpg ON tpg.id=pg.adms_types_pgs_id
                    LEFT JOIN adms_sits_pgs AS sit ON sit.id=pg.adms_sits_pgs_id
                    LEFT JOIN adms_colors AS col ON col.id=sit.adms_color_id 
                    WHERE metodo LIKE :search_metodo
                    ORDER BY pg.id DESC
                    LIMIT :limit OFFSET :offset", "search_metodo={$this->searchMetodoValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listUsers->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma página encontrada!</p>";
            $this->result = false;
        }
    }

    
}
