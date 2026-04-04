<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar Tipos de equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListTypeEquip
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

    /** @var string|null $searchTypeEquip Recebe o tipo*/
    private string|null $searchTypeEquip;

    /** @var string|null $searchTypeEquipValue Recebe o valor do tipo*/
    private string|null $searchTypeEquipValue;

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
    public function listTypeEquip(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-type-equip/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_type_equip");
        $this->resultPg = $pagination->getResult();

        $listTypeEquip = new \App\adms\Models\helper\AdmsRead();
        $listTypeEquip->fullRead("SELECT id, name FROM adms_type_equip ORDER BY name
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");
        $this->resultBd = $listTypeEquip->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma Tipo de equipamento encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa dos tipos dos equipamentos na tabela adms_type_equip e lista as informacoes na view
     * Recebe o paramentro "typo" para que seja feita a paginacao do resultado
     * Recebe o paramentro "searchTypoEquip" para que seja feita a pesquisa pelo nome dos tipos dos equipamentos
     * @param integer|null $page
     * @param string|null $searchTypeEquip
     * @return void
     */
    public function listSearchTypeEquip(int $page = null, string|null $searchTypeEquip): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchTypeEquip = trim($searchTypeEquip);

        $this->searchTypeEquipValue = "%" . $this->searchTypeEquip . "%";

        if ((!empty($this->searchTypeEquip))) {
            $this->searchNomeTypeEquip();
        } else {
            $this->searchNomeTypeEquip();
        }
    }

    /**
     * Metodo pesquisar pelo nome dos tipos dos equipamentos
     * @return void
     */
    public function searchNomeTypeEquip(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-type-equip/index', "?search_type_equip={$this->searchTypeEquip}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_type_equip
                                WHERE name LIKE :search_type_equip", "search_type_equip={$this->searchTypeEquipValue}");
        $this->resultPg = $pagination->getResult();

        $listTypeEquip = new \App\adms\Models\helper\AdmsRead();
        $listTypeEquip->fullRead("SELECT id, name FROM adms_type_equip  WHERE name LIKE :search_name
                    ORDER BY id DESC
                    LIMIT :limit OFFSET :offset", "search_name={$this->searchTypeEquipValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listTypeEquip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma tipo de equipamento encontrado!</p>";
            $this->result = false;
        }
    }
}
