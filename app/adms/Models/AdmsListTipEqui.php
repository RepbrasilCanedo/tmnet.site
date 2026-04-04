<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar tipos de equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListTipEqui
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
    private string|null $searchEmp;

    /** @var string|null $searchName Recebe o nome da cor */
    private string|null $searchNameValue;

    /** @var string|null $searchEmail Recebe o nome da cor em hexadecimal */
    private string|null $searchEmpValue;

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
    public function listTipEqui(int $page):void
    {
        $this->page = (int) $page ? $page : 1;

        if ($_SESSION['adms_access_level_id'] > 2) {
                //Acessa se for Cliente Adm ou Suporte do Cliente
            if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] == 12)) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-tip-equi/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_type_equip WHERE empresa_id= :empresa_id","empresa_id={$_SESSION['emp_user']}");
                $this->resultPg = $pagination->getResult();

                $listTipEqui = new \App\adms\Models\helper\AdmsRead();
                $listTipEqui->fullRead("SELECT tip.id as id_tip, tip.name as name_tip, tip.empresa_id, sit.name as name_sit FROM adms_type_equip as tip
                                    INNER JOIN adms_sit_equip AS sit ON sit.id=tip.sit_id 
                                    INNER JOIN adms_emp_principal AS emp ON emp.id=tip.empresa_id
                                    WHERE tip.empresa_id= :empresa_id
                                    ORDER BY name_tip
                                    LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listTipEqui->getResult();        
                if($this->resultBd){
                    $this->result = true;
                }else{
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum tipo de equipamento encontrado!</p>";
                    $this->result = false;
                }
            }
        } else {
                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-colors/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_type_equip");
                $this->resultPg = $pagination->getResult();

                $listTipEqui = new \App\adms\Models\helper\AdmsRead();
                $listTipEqui->fullRead("SELECT tip.id as id_tip, tip.name as name_tip, tip.empresa_id, emp.nome_fantasia as nome_fantasia, sit.name as name_sit FROM adms_type_equip as tip
                                    INNER JOIN adms_sit_equip AS sit ON sit.id=tip.sit_id 
                                    INNER JOIN adms_emp_principal AS emp ON emp.id=tip.empresa_id
                                    ORDER BY name_tip
                                    LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listTipEqui->getResult();        
                if($this->resultBd){
                    $this->result = true;
                }else{
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum tipo de equipamento encontrado!</p>";
                    $this->result = false;
                }
        }
    }

    /**
     * Metodo faz a pesquisa das cores na tabela adms_colors e lista as informacoes na view
     * Recebe o paramentro "page" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_name" para que seja feita a pesquisa pelo nome da cor
     * Recebe o paramentro "search_color" para que seja feita a pesquisa pelo nome em hexadecimal
     * @param integer|null $page
     * @param string|null $search_name
     * @return void
     */
    
    public function listSearcTipEqui(int $page, string|null $search_name): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchName = trim($search_name);

        $this->searchNameValue = "%" . $this->searchName . "%";

        if ((!empty($this->searchName))){
            $this->searchName();
        }else{
            $this->listTipEqui($this->page);            
        }
    }

    /**
     * Metodo pesquisar pelo nome da cor e cor em hexadecimal
     * @return void
     */
    public function searchName(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-tip-equi/index', "?search_name={$this->searchNameValue}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result, empresa_id, name FROM adms_type_equip WHERE empresa_id= :empresa_id AND name LIKE :search_name ","empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}");
        $this->resultPg = $pagination->getResult();

        $listTipEqui = new \App\adms\Models\helper\AdmsRead();
        $listTipEqui->fullRead("SELECT tip.id as id_tip, tip.name as name_tip, tip.empresa_id, sit.name as name_sit FROM adms_type_equip as tip
                                    INNER JOIN adms_sit_equip AS sit ON sit.id=tip.sit_id 
                                    WHERE tip.empresa_id= :empresa_id AND tip.name LIKE :search_name 
                                    ORDER BY name_tip
                                    LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
        $this->resultBd = $listTipEqui->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum tipo de equipamento encontrado!</p>";
            $this->result = false;
        }
    }

    
}
