<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar a configuração de emails do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListConfEmails
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

    /** @var string|null $searchName Recebe o titulo */
    private string|null $searchTitle;

    /** @var string|null $searchEmail Recebe o e-mail */
    private string|null $searchEmail;

    /** @var string|null $searchName Recebe o titulo */
    private string|null $searchTitleValue;

    /** @var string|null $searchEmail Recebe o e-mail */
    private string|null $searchEmailValue;

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
     * Metodo faz a pesquisa das configurações de e-mail na tabela adms_confs_emails e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listConfEmails(int $page = null):void
    {
        $this->page = (int) $page ? $page : 1;

        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-conf-emails/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_confs_emails");
        $this->resultPg = $pagination->getResult();

        $listConfEmails = new \App\adms\Models\helper\AdmsRead();
        $listConfEmails->fullRead("SELECT id, title, name, email
                FROM adms_confs_emails
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listConfEmails->getResult();        
        if($this->resultBd){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhum e-mail encontrado!</p>";
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
    public function listSearchConfEmails(int $page = null, string|null $search_title, string|null $search_email): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchTitle = trim($search_title);
        $this->searchEmail = trim($search_email);

        $this->searchTitleValue = "%" . $this->searchTitle . "%";
        $this->searchEmailValue = "%" . $this->searchEmail . "%";

        if ((!empty($this->searchTitle)) and (!empty($this->searchEmail))) {
            $this->searchConfTitleEmail();
        } elseif ((!empty($this->searchTitle)) and (empty($this->searchEmail))) {
            $this->searchConfTitle();
        } elseif ((empty($this->searchTitle)) and (!empty($this->searchEmail))) {
            $this->searchConfEmail();
        } else {
            $this->searchConfTitleEmail();
        }
    }

    /**
     * Metodo pesquisar pelo nome da cor e cor em hexadecimal
     * @return void
     */
    public function searchConfTitleEmail(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-conf-emails/index', "?search_title={$this->searchTitle}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_confs_emails
                                WHERE title LIKE :search_title
                                OR email LIKE :search_email", "search_title={$this->searchTitleValue}&search_email={$this->searchEmailValue}");                        
        $this->resultPg = $pagination->getResult();

        $listConfEmails = new \App\adms\Models\helper\AdmsRead();
        $listConfEmails->fullRead("SELECT id, title, name, email
                    FROM adms_confs_emails 
                    WHERE title LIKE :search_title
                    OR email LIKE :search_email
                    ORDER BY id DESC
                    LIMIT :limit OFFSET :offset", "search_title={$this->searchTitleValue}&search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listConfEmails->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma configuração de e-mail encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo titulo da configuração de e-mail
     * @return void
     */
    public function searchConfTitle(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-conf-emails/index', "?search_title={$this->searchTitle}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_confs_emails
                                WHERE title LIKE :search_title", "search_title={$this->searchTitleValue}");
        $this->resultPg = $pagination->getResult();

        $listConfEmails = new \App\adms\Models\helper\AdmsRead();
        $listConfEmails->fullRead("SELECT id, title, name, email
                                FROM adms_confs_emails 
                                WHERE title LIKE :search_title
                                ORDER BY id DESC
                                LIMIT :limit OFFSET :offset", "search_title={$this->searchTitleValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listConfEmails->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma configuração de e-mail encontrada!</p>";
            $this->result = false;
        }
    }

     /**
     * Metodo pesquisar pelo email da configuração de e-mail
     * @return void
     */
    public function searchConfEmail(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-conf-emails/index', "?search_title={$this->searchTitle}&search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result 
                                FROM adms_confs_emails
                                WHERE email LIKE :search_email", "search_email={$this->searchEmailValue}");
        $this->resultPg = $pagination->getResult();

        $listConfEmails = new \App\adms\Models\helper\AdmsRead();
        $listConfEmails->fullRead("SELECT id, title, name, email
                                FROM adms_confs_emails 
                                WHERE email LIKE :search_email
                                ORDER BY id DESC
                                LIMIT :limit OFFSET :offset", "search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listConfEmails->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma configuração de e-mail encontrada!</p>";
            $this->result = false;
        }
    }
    
}
