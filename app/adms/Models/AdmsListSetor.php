<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar Empresa do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListSetor
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

    /** @var string|null $sesearchRazao Recebe a razao social da empresa*/
    private string|null $searchSetor;

    /** @var string|null $sesearchRazao Recebe o valor da razao social da empresa*/
    private string|null $searchSetorValue;

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
     * Metodo faz a pesquisa dos Setores da empresa na tabela "adms_setor" e lista as informações na view
     * Recebe como parametro "page" para fazer a paginação
     * @param integer|null $page
     * @return void
     */
    public function listSetor(int $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {
            //Operador
            if ($_SESSION['adms_access_level_id'] == 10) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-setor/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_setor WHERE empresa_id= :empresa", "empresa={$_SESSION['emp_user']}");
                $this->resultPg = $pagination->getResult();

                $listSetor = new \App\adms\Models\helper\AdmsRead();
                $listSetor->fullRead("SELECT setor.id, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
                INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id 
                WHERE empresa_id= :empresa ORDER BY name 
                            LIMIT :limit OFFSET :offset", "empresa={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
                $this->resultBd = $listSetor->getResult();

                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma Empresa encontrada!</p>";
                    $this->result = false;
                }
                //Cliente Adm
            } elseif ($_SESSION['adms_access_level_id'] == 4) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-setor/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_setor WHERE cont_id = :contrato", "contrato={$_SESSION['set_Contr']}");
                $this->resultPg = $pagination->getResult();

                $listSetor = new \App\adms\Models\helper\AdmsRead();
                $listSetor->fullRead("SELECT setor.id, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
                INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id  WHERE cont_id = :contrato ORDER BY name 
                            LIMIT :limit OFFSET :offset", "contrato={$_SESSION['set_Contr']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
                $this->resultBd = $listSetor->getResult();

                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma Empresa encontrada!</p>";
                    $this->result = false;
                }
                //Cliente Suporte
            } elseif ($_SESSION['adms_access_level_id'] == 13){
                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-setor/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_setor WHERE cont_id = :contrato", "contrato={$_SESSION['set_Contr']}");
                $this->resultPg = $pagination->getResult();

                $listSetor = new \App\adms\Models\helper\AdmsRead();
                $listSetor->fullRead("SELECT setor.id, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
                INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id  WHERE cont_id = :contrato ORDER BY name 
                            LIMIT :limit OFFSET :offset", "contrato={$_SESSION['set_Contr']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
                $this->resultBd = $listSetor->getResult();

                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma Empresa encontrada!</p>";
                    $this->result = false;
                }
            }
        } else {

            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-setor/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(setor.id) AS num_result, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
            INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id");
            $this->resultPg = $pagination->getResult();

            $listSetor = new \App\adms\Models\helper\AdmsRead();
            $listSetor->fullRead("SELECT setor.id, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
            INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id
                            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");
            $this->resultBd = $listSetor->getResult();

            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum setor encontrado!</p>";
                $this->result = false;
            }
        }
    }

    /**
     * Metodo faz a pesquisa dos setores da Empresas na tabela adms_empresa_unid e lista as informacoes na view
     * Recebe o paramentro "page" para que seja feita a paginacao do resultado
     * Recebe o paramentro "searchSetor" para que seja feita a pesquisa pelo nome do setor da Empresa
     * @param integer|null $page
     * @param string|null $searchSetor
     * @return void
     */
    public function listSearchSetor(int $page = null, string|null $searchSetor): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchSetor = trim($searchSetor);

        $this->searchSetorValue = "%" . $this->searchSetor . "%";

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-setor/index', "?search_setor={$this->searchSetor}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(setor.id) AS num_result, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
        INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id");
        $this->resultPg = $pagination->getResult();

        $listSetor = new \App\adms\Models\helper\AdmsRead();
        $listSetor->fullRead(
            "SELECT setor.id, setor.name, emp.nome_fantasia as nome_fantasia_emp FROM adms_setor AS setor
        INNER JOIN adms_empresa AS emp ON emp.id=setor.empresa_id
                    WHERE name LIKE :search_name ORDER BY name LIMIT :limit OFFSET :offset",
            "search_name={$this->searchSetorValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}"
        );

        $this->resultBd = $listSetor->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma Empresa encontrada!</p>";
            $this->result = false;
        }
    }
}
