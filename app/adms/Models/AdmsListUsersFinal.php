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
class AdmsListUsersFinal
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

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmail;

    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array|null $listRegistryAdd;

    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchNameValue;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmpresaValue;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmailValue;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchContratoValue;

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

            if ($_SESSION['adms_access_level_id'] == 4) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users-final/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users_final usr
                                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                        WHERE empresa_id = :empresa_id and lev.order_levels >:order_levels", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}");
                $this->resultPg = $pagination->getResult();

                $listUsers = new \App\adms\Models\helper\AdmsRead();
                $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social AS razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id, usr.empresa_id,
                            sit.name AS name_sit, col.color FROM adms_users_final AS usr
                            INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id 
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                            WHERE usr.empresa_id = :empresa_id and lev.order_levels >:order_levels
                            ORDER BY usr.id DESC
                            LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listUsers->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                    $this->result = false;
                }
            }elseif ($_SESSION['adms_access_level_id'] == 12) {

                // Verifica se o usuario final esta defiinido para um cliente com suporte especifico
                $clieUser = new \App\adms\Models\helper\AdmsRead();             
                $clieUser->fullRead("SELECT user_id, empresa_id FROM adms_user_clie WHERE user_id= :user_id AND empresa_id= :empresa_id","user_id={$_SESSION['user_id']}&empresa_id={$_SESSION['emp_user']}");
                $this->resultBd = $clieUser->getResult();
        
                if($this->resultBd){
                    $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users-final/index');
                    $pagination->condition($this->page, $this->limitResult);
                    $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users_final usr
                                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                            WHERE empresa_id = :empresa_id and lev.order_levels >:order_levels AND usr.cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']})" , "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}");
                    $this->resultPg = $pagination->getResult();

                    $listUsers = new \App\adms\Models\helper\AdmsRead();
                    $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social AS razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id, usr.empresa_id,
                                sit.name AS name_sit, col.color FROM adms_users_final AS usr
                                INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id 
                                INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                                INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                                INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                                WHERE usr.empresa_id = :empresa_id and lev.order_levels >:order_levels AND usr.cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']})
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
                    $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users-final/index');
                    $pagination->condition($this->page, $this->limitResult);
                    $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users_final usr
                                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                            WHERE empresa_id = :empresa_id and lev.order_levels >:order_levels" , "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}");
                    $this->resultPg = $pagination->getResult();

                    $listUsers = new \App\adms\Models\helper\AdmsRead();
                    $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social AS razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id, usr.empresa_id,
                                sit.name AS name_sit, col.color FROM adms_users_final AS usr
                                INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id 
                                INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                                INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                                INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                                WHERE usr.empresa_id = :empresa_id and lev.order_levels >:order_levels
                                ORDER BY usr.id DESC
                                LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&order_levels={$_SESSION['order_levels']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                    $this->resultBd = $listUsers->getResult();
                    if ($this->resultBd) {
                        $this->result = true;
                    } else {
                        $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                        $this->result = false;
                    }

                }


                
            }
        } else {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users-final/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(usr.id) AS num_result FROM adms_users_final usr
                                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                                    WHERE lev.order_levels >:order_levels", "order_levels=" . $_SESSION['order_levels']);
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social AS razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id, usr.empresa_id,
                        sit.name AS name_sit, col.color FROM adms_users_final AS usr
                        INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id 
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
    public function listSearchUsers(int $page, string|null $search_name, string|null $search_empresa, string|null $search_email): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchName = $search_name;
        $this->searchEmpresa = $search_empresa;
        $this->searchEmail = $search_email;

        $this->searchNameValue = $this->searchName . "%";
        $this->searchEmpresaValue = $this->searchEmpresa . "%";
        $this->searchEmailValue = $this->searchEmail . "%";

        if ((!empty($this->searchName))) {
            $this->searchUsersName();
        } elseif ((!empty($this->searchEmpresa))) {
            $this->searchUsersEmpresa();
        } elseif ((!empty($this->searchEmail))) {
            $this->searchUsersEmail();
        } else {
            $this->listUsers($this->page);
        }
    }

    /**
     * Metodo pesquisar pelo nome e e-mail
     * @return void
     */
    public function searchUsersName(): void
    {
        if ($_SESSION['adms_access_level_id'] > 2) {

            if ($_SESSION['adms_access_level_id'] == 4) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchName}");
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                        WHERE (name LIKE :search_name)", "search_name={$this->searchNameValue}");
                $this->resultPg = $pagination->getResult();

                $listUsers = new \App\adms\Models\helper\AdmsRead();
                $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp,  emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                            sit.name AS name_sit, col.color FROM adms_users_final AS usr
                            INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                            WHERE usr.name LIKE :search_name ORDER BY usr.id DESC
                            LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listUsers->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                    $this->result = false;
                }
            }elseif ($_SESSION['adms_access_level_id'] == 12) {

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchName}");
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final WHERE (name LIKE :search_name) AND cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']})", "search_name={$this->searchNameValue}");
                $this->resultPg = $pagination->getResult();

                $listUsers = new \App\adms\Models\helper\AdmsRead();
                $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp,  emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                            sit.name AS name_sit, col.color FROM adms_users_final AS usr
                            INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                            WHERE usr.name LIKE :search_name AND cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']}) ORDER BY usr.id DESC
                            LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listUsers->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                    $this->result = false;
                }
            }    
           
            



        } else {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchName}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                WHERE (usr.name LIKE :search_name)", "search_name={$this->searchNameValue}");
            $this->resultPg = $pagination->getResult();

            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color FROM adms_users_final AS usr
                        INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                    WHERE (usr.name LIKE :search_name)
                    ORDER BY usr.id DESC
                    LIMIT :limit OFFSET :offset", "search_name={$this->searchNameValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

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
     * Metodo pesquisar pela empresa
     * @return void
     */
    public function searchUsersEmpresa(): void
    {
        if ($_SESSION['adms_access_level_id'] > 2) {
            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_name={$this->searchEmpresa}");
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                    WHERE (cliente_id= :empresa_user)", "empresa_user={$this->searchEmpresaValue}"
            );
            $this->resultPg = $pagination->getResult();
    
            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color FROM adms_users_final AS usr
                        INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (usr.cliente_id  LIKE :empresa_user)
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "empresa_user={$this->searchEmpresaValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
    
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
            $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                    WHERE (emp.razao_social LIKE :empresa_user)", "empresa_user={$this->searchEmpresaValue}"
            );
            $this->resultPg = $pagination->getResult();
    
            $listUsers = new \App\adms\Models\helper\AdmsRead();
            $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp, emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color FROM adms_users_final AS usr
                        INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                        WHERE (emp.razao_social LIKE :empresa_user)
                        ORDER BY usr.id DESC
                        LIMIT :limit OFFSET :offset", "empresa_user={$this->searchEmpresaValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
    
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
     * Metodo pesquisar pelo e-mail
     * @return void
     */
    public function searchUsersEmail(): void
    {
        if ($_SESSION['adms_access_level_id'] > 2) {

            if ($_SESSION['adms_access_level_id'] == 4){

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_email={$this->searchEmail}");
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                        WHERE (empresa_id= :cliente_id) AND (email LIKE :search_email)",
                                        "cliente_id={$_SESSION['emp_user']}&search_email={$this->searchEmailValue}"
                );
                $this->resultPg = $pagination->getResult();
        
                $listUsers = new \App\adms\Models\helper\AdmsRead();
                $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp,  emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                            sit.name AS name_sit, col.color FROM adms_users_final AS usr
                            INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                            WHERE (usr.empresa_id = :cliente_id) AND (usr.email LIKE :search_email)
                            ORDER BY usr.id DESC
                            LIMIT :limit OFFSET :offset", "cliente_id={$_SESSION['emp_user']}&search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
        
                $this->resultBd = $listUsers->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                    $this->result = false;
                }

            }elseif ($_SESSION['adms_access_level_id'] == 12){

                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_email={$this->searchEmail}");
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final
                                        WHERE (empresa_id= :cliente_id) AND (email LIKE :search_email) AND cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']})",
                                        "cliente_id={$_SESSION['emp_user']}&search_email={$this->searchEmailValue}"
                );
                $this->resultPg = $pagination->getResult();
        
                $listUsers = new \App\adms\Models\helper\AdmsRead();
                $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp,  emp.nome_fantasia AS nome_fantasia_emp, usr.adms_sits_user_id,
                            sit.name AS name_sit, col.color FROM adms_users_final AS usr
                            INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id 
                            WHERE (usr.empresa_id = :cliente_id) AND (usr.email LIKE :search_email) AND cliente_id  IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']})
                            ORDER BY usr.id DESC
                            LIMIT :limit OFFSET :offset", "cliente_id={$_SESSION['emp_user']}&search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");
        
                $this->resultBd = $listUsers->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum usuário encontrado!</p>";
                    $this->result = false;
                }

            }
        } else {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-users/index', "?search_email={$this->searchEmail}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_users_final WHERE (usr.email LIKE :search_email)", "search_email={$this->searchEmailValue}"
        );
        $this->resultPg = $pagination->getResult();

        $listUsers = new \App\adms\Models\helper\AdmsRead();
        $listUsers->fullRead("SELECT usr.id, usr.name AS name_usr, usr.email, emp.razao_social razao_social_emp, usr.adms_sits_user_id,
                        sit.name AS name_sit, col.color FROM adms_users_final AS usr
                        INNER JOIN adms_clientes AS emp ON emp.id=usr.cliente_id
                        INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                        INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                        INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id  
                    WHERE (usr.email LIKE :search_email)
                    ORDER BY usr.id DESC
                    LIMIT :limit OFFSET :offset", "search_email={$this->searchEmailValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

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
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect()
    {      
        $list = new \App\adms\Models\helper\AdmsRead();

        if ($_SESSION['adms_access_level_id'] > 2) {

            if ($_SESSION['adms_access_level_id'] == 4){
                $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes
                WHERE empresa= :empresa  ORDER BY nome_fantasia", "empresa={$_SESSION['emp_user']}");
                $registry['nome_clie'] = $list->getResult();

            }elseif ($_SESSION['adms_access_level_id'] == 12){
                // Verifica se o cliente esta defiinido para um suporte especifico
                $clieUser = new \App\adms\Models\helper\AdmsRead();             
                $clieUser->fullRead("SELECT user_id, empresa_id FROM adms_user_clie WHERE user_id= :user_id AND empresa_id= :empresa_id","user_id={$_SESSION['user_id']}&empresa_id={$_SESSION['emp_user']}");
                $this->resultBd = $clieUser->getResult();

                if($this->resultBd){
                    $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes
                    WHERE empresa= :empresa AND id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$_SESSION['user_id']}) ORDER BY nome_fantasia", "empresa={$_SESSION['emp_user']}");
                    $registry['nome_clie'] = $list->getResult();

                } else {
                    $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes
                    WHERE empresa= :empresa ORDER BY nome_fantasia", "empresa={$_SESSION['emp_user']}");
                    $registry['nome_clie'] = $list->getResult();

                }

            }
                
                
        } else {

            $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes ORDER BY nome_fantasia");
            $registry['nome_clie'] = $list->getResult();

            
        }

        $this->listRegistryAdd = ['nome_clie' => $registry['nome_clie']];
        return $this->listRegistryAdd;
    }
}
