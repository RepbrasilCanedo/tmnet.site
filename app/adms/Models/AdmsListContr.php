<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar Contratos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListContr
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int $page Recebe o número página */
    private int $page;

    /** @var string|null $searchEmail Recebe o metodo */
    private int|null $searchId;

    /** @var string|null $searchEmail Recebe o metodo */
    private int|null $searchType;

    /** @var string|null $searchEmail Recebe o metodo */
    private int|null $searchServ;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchEmp;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchIdValue;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchTypeValue;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchServValue;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchEmpValue;

    /** @var int $page Recebe a quantidade de registros que deve retornar do banco de dados */
    private int $limitResult = 40;

    /** @var string|null $page Recebe a páginação */
    private string|null $resultPg;

    /** @var array|null $listRegistryAdd Recebe os registros do banco de dados */
    private array|null $listRegistryAdd;

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
     * Metodo faz a pesquisa dos contratos na tabela adms_contr e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listContr(int $page): void
    {
        $this->page = (int) $page ? $page : 1;
        
        if ($_SESSION['adms_access_level_id'] > 2) {
            
            //Se for 4 -> Cliente Administrativo e suporte cliente
            if ($_SESSION['adms_access_level_id'] == 4) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-contr/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_contr WHERE empresa_id= :empresa_id", "empresa_id={$_SESSION['emp_user']}");
                $this->resultPg = $pagination->getResult();

                $listContr = new \App\adms\Models\helper\AdmsRead();
                $listContr->fullRead("SELECT cont.id as id_cont, cont.name as name_cont, sit.name AS situacao, emp.nome_fantasia AS nome_fantasia_emp
                FROM adms_contr AS cont 
                INNER JOIN adms_emp_principal AS emp ON emp.id=cont.empresa_id      
                INNER JOIN adms_contr_sit AS sit ON sit.id=cont.sit_cont   
                WHERE cont.empresa_id = :empresa_id  
                LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listContr->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma contrato encontrado!</p>";
                    $this->result = false;
                }
            }
        } else {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-contr/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(cont.id) AS num_result FROM adms_contr AS cont");
            $this->resultPg = $pagination->getResult();

            $listContr = new \App\adms\Models\helper\AdmsRead();
            $listContr->fullRead("SELECT cont.id as id_cont, cont.name as name_cont, sit.name AS situacao, emp.nome_fantasia AS nome_fantasia_emp
                FROM adms_contr AS cont 
                INNER JOIN adms_emp_principal AS emp ON emp.id=cont.empresa_id      
                INNER JOIN adms_contr_sit AS sit ON sit.id=cont.sit_cont  
            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listContr->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum Contrato encontrado!</p>";
                $this->result = false;
            }
        }
    }

    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        $list->fullRead("SELECT id, name FROM adms_contr_type  ORDER BY name");
        $registry['type_cont'] = $list->getResult();

        $list->fullRead("SELECT id id_serv, name serv_name FROM adms_contr_service as serv  ORDER BY serv_name ASC");
        $registry['name_serv'] = $list->getResult();

        $list->fullRead("SELECT id, razao_social, contrato FROM adms_empresa  ORDER BY razao_social ASC");
        $registry['nome_emp'] = $list->getResult();




        $this->listRegistryAdd = ['name_serv' => $registry['name_serv'], 'type_cont' => $registry['type_cont'], 'nome_emp' => $registry['nome_emp']];

        return $this->listRegistryAdd;
    }
}
