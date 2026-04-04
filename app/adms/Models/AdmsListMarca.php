<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar marcas dos equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListMarca
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

    /** @var string|null $searchMarca Recebe a marca*/
    private string|null $searchMarca;

    /** @var string|null $searchMarca Recebe o valor da marca*/
    private string|null $searchMarcaValue;

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
     * Metodo faz a pesquisa das Empresas na tabela "adms_empresas" e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * @param integer|null $page
     * @return void
     */
    public function listMarca(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-marca/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(emp.id) AS num_result FROM adms_empresa_unid AS emp");
        $this->resultPg = $pagination->getResult();

        $listMarca = new \App\adms\Models\helper\AdmsRead();
        $listMarca->fullRead("SELECT id, name FROM adms_marca ORDER BY name 
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");
        $this->resultBd = $listMarca->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma marca de equipamento encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa das marcas dos equipamentos na tabela adms_empresa_unid e lista as informacoes na view
     * Recebe o paramentro "marca" para que seja feita a paginacao do resultado
     * Recebe o paramentro "searchMarcar" para que seja feita a pesquisa pelo nome das marcas dos equipamentos
     * @param integer|null $page
     * @param string|null $searchMarcar
     * @return void
     */
    public function listSearchMarca(int $page = null, string|null $searchMarca): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchMarca = trim($searchMarca);

        $this->searchMarcaValue = "%" . $this->searchMarca . "%";

        if ((!empty($this->searchMarca))) {
            $this->searchNomeMarca();
        } else {
            $this->searchNomeMarca();
        }
    }

    /**
     * Metodo pesquisar pelo nome das marcas dos equipamentos
     * @return void
     */
    public function searchNomeMarca(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-marca/index', "?search_marca={$this->searchMarca}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_marca
                                WHERE name LIKE :search_marca", "search_marca={$this->searchMarcaValue}");
        $this->resultPg = $pagination->getResult();

        $listMarca = new \App\adms\Models\helper\AdmsRead();
        $listMarca->fullRead("SELECT id, name FROM adms_marca  WHERE name LIKE :search_name
                    ORDER BY id DESC
                    LIMIT :limit OFFSET :offset", "search_name={$this->searchMarcaValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listMarca->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma marca de equipamento encontrada!</p>";
            $this->result = false;
        }
    }
}
