<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar os usuários do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListUsers
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int $page Recebe o número página */
    private int $page;

    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmpresa;

    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchNameValue;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmpresaValue;

    /** @var int $page Recebe a quantidade de registros que deve retornar do banco de dados */
    private int $limitResult = 40;

    /** @var string|null $page Recebe a páginação */
    private string|null $resultPg;

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
     * Metodo faz a pesquisa dos usuários na tabela adms_users e lista as informações na view
     * Recebe o paramentro "page" para que seja feita a paginação do resultado
     * @param integer|null $page
     * @return void
     */
    public function listUsers(int $page): void
    {
        $this->page = (int) $page ? $page : 1;

        if ($_SESSION['adms_access_level_id'] > 2) {

            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    WHERE usr.empresa_id= :empresa_id and lev.order_levels >:order_levels", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}");
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.email, usr.telefone, emp.razao_social razao_social_emp, usr.adms_sits_user_id, usr.empresa_id,
                        sit.name AS name_sit, col.color, lev.name AS name_lev FROM adms_users AS usr
                        INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE empresa_id = :empresa_id and lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        } else {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    WHERE lev.order_levels >:order_levels", "order_levels=" . $_SESSION['order_levels']);
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.email, usr.telefone, emp.razao_social razao_social_emp, usr.adms_sits_user_id, usr.empresa_id,
                        sit.name AS name_sit, col.color, lev.name AS name_lev FROM adms_users AS usr
                        INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "order_levels={$_SESSION['order_levels']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        }
    }

    /**
     * Metodo faz a pesquisa dos usuarios na tabela adms_users e lista as informacoes na view
     * Recebe o paramentro "page" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_name" para que seja feita a pesquisa pelo nome do usuario
     * Recebe o paramentro "search_email" para que seja feita a pesquisa pelo email do usuario
     * @param integer|null $page
     * @param string|null $search_name
     * @param string|null $search_email
     * @return void
     */
    
    
    public function listSearchUsers(int $page, string|null $search_name): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchName = $search_name;

        $this->searchNameValue = $this->searchName . "%";

        if ((!empty($this->searchName))) {
            $this->searchUsersName();
        } else {
            $this->listUsers($this->page);
        }
    }

    /**
     * Metodo pesquisar pelo nome do usuario da empresa
     * @return void
     */
    public function searchUsersName(): void
    {
        if ($_SESSION['adms_access_level_id'] > 2) {
           
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchName}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    WHERE (empresa_id= :empresa_id) AND (usr.name LIKE :search_name) and lev.order_levels >:order_levels
                                    ", "order_levels={$_SESSION['order_levels']}&empresa_id={$_SESSION['emp_user']}&search_name={$this->searchNameValue}");
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.email, usr.telefone, usr.adms_sits_user_id,
                        sit.name AS name_sit, lev.name AS name_lev, col.color FROM adms_users AS usr
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (empresa_id= :empresa_id) AND (usr.name LIKE :search_name) and lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        } else {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchName}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    WHERE (usr.name LIKE :search_name) and lev.order_levels >:order_levels
                                    ", "order_levels={$_SESSION['order_levels']}&search_name={$this->searchNameValue}");
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.email, usr.telefone, usr.adms_sits_user_id,
                        sit.name AS name_sit, lev.name AS name_lev, col.color FROM adms_users AS usr
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (usr.name LIKE :search_name) and lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "order_levels={$_SESSION['order_levels']}&search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        }
    }
    /**
     * Metodo pesquisar pela empresa do usuario
     * @return void
     */
    public function searchUsersEmpresa(): void
    {
        if (($_SESSION['adms_access_level_id'] > 2)) {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchEmpresa}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result, emp.razao_social FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                                    WHERE (empresa_id= :empresa_id) AND (emp.razao_social LIKE :empresa_user) and lev.order_levels >:order_levels", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&empresa_user={$this->searchEmpresaValue}"
            );
            $this->resultPg = $pagination->getResult();
    
            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr,usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.email, usr.telefone, emp.razao_social razao_social_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color  lev.name AS name_lev FROM adms_users AS usr
                        INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (empresa_id= :empresa_id) AND (emp.razao_social  LIKE :empresa_user) and lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&empresa_user={$this->searchEmpresaValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
    
            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        }else{
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchEmpresaValue}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination(
                "SELECT COUNT(usr.id) AS num_result, emp.razao_social FROM adms_users usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id 
                                    WHERE (emp.razao_social LIKE :empresa_user) and lev.order_levels >:order_levels", "order_levels={$_SESSION['order_levels']}&empresa_user={$this->searchEmpresaValue}"
            );
            $this->resultPg = $pagination->getResult();
    
            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.email, usr.telefone, cont.num_cont AS num_cont, emp.razao_social razao_social_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color FROM adms_users AS usr
                        INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                        INNER JOIN adms_contr AS cont ON cont.id=usr.contr_id  
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (emp.razao_social LIKE :empresa_user) and lev.order_levels >:order_levels
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "order_levels={$_SESSION['order_levels']}&empresa_user={$this->searchEmpresaValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
    
            $this->resultBd = $listUsers->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                $this->result = false;
            }
        }
        
    }
}
