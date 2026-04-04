<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar modelos dos equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListModelo
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

    /** @var string|null $searchModelo Recebe o modelo*/
    private string|null $searchModelo;

    /** @var string|null $searchModeloValue Recebe o valor do modelo*/
    private string|null $searchModeloValue;

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
    public function listModelo(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-modelo/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_model");
        $this->resultPg = $pagination->getResult();

        $listModelo = new \App\adms\Models\helper\AdmsRead();
        $listModelo->fullRead("SELECT id, name FROM adms_model ORDER BY name 
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");
        $this->resultBd = $listModelo->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma modelo de equipamento encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos modelos dos equipamentos na tabela adms_empresa_unid e lista as informacoes na view
     * Recebe o paramentro "modelo" para que seja feita a paginacao do resultado
     * Recebe o paramentro "searchModelo" para que seja feita a pesquisa pelo nome dos modelos dos equipamentos
     * @param integer|null $page
     * @param string|null $searchModelo
     * @return void
     */
    public function listSearchModelo(int $page = null, string|null $searchModelo): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchModelo = trim($searchModelo);

        $this->searchModeloValue = "%" . $this->searchModelo . "%";

        if ((!empty($this->searchModelo))) {
            $this->searchNomeModelo();
        } else {
            $this->searchNomeModelo();
        }
    }

    /**
     * Metodo pesquisar pelo nome dos modelos dos equipamentos
     * @return void
     */
    public function searchNomeModelo(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-modelo/index', "?search_modelo={$this->searchModelo}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_model
                                WHERE name LIKE :search_modelo", "search_modelo={$this->searchModeloValue}");
        $this->resultPg = $pagination->getResult();

        $listModelo = new \App\adms\Models\helper\AdmsRead();
        $listModelo->fullRead("SELECT id, name FROM adms_model  WHERE name LIKE :search_name
                    ORDER BY id DESC
                    LIMIT :limit OFFSET :offset", "search_name={$this->searchModeloValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listModelo->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum modelo de equipamento encontrada!</p>";
            $this->result = false;
        }
    }
}
