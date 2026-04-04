<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsListEquip
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
    private string|null $searchEquip;

    /** @var string|null $searchEmail Recebe o metodo */
    private string|null $searchEmp;

    /** @var string|null $searchName Recebe o controller */
    private string|null $searchEquipValue;

    /** @var string|null $searchEmail Recebe o metodo */
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
     * Metodo faz a pesquisa dos equipamentos na tabela adms_equipamentos e lista as informações na view
     * Recebe o paramentro "equip" para que seja feita a paginação do resultado
     * @param integer|null $equip
     * @return void
     */
    public function listEquip(int $page): void
    {
        $this->page = (int) $page ? $page : 1;



        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {

            if ($_SESSION['adms_access_level_id'] == 10) {
                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos WHERE empresa_id= :empresa_id","empresa_id={$_SESSION['emp_user']}");
                $this->resultPg = $pagination->getResult();

                $listEquip = new \App\adms\Models\helper\AdmsRead();
                $listEquip->fullRead("SELECT equip.id, equip.name, typ.name name_typ, emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
                FROM adms_equipamentos AS equip 
                LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
                LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
                LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id  
                WHERE equip.empresa_id= :empresa_id ORDER BY equip.name ASC
                LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listEquip->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma Equipamento encontrado!</p>";
                    $this->result = false;
                } 
            }else if ($_SESSION['adms_access_level_id'] == 13) {
                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos WHERE cont_id = :cont_id","cont_id={$_SESSION['set_Contr']}");
                $this->resultPg = $pagination->getResult();

                $listEquip = new \App\adms\Models\helper\AdmsRead();
                $listEquip->fullRead("SELECT equip.id, equip.name, typ.name name_typ, emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
                FROM adms_equipamentos AS equip 
                LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
                LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
                LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id  
                WHERE equip.cont_id = :cont_id  
                ORDER BY equip.name ASC
                LIMIT :limit OFFSET :offset", "cont_id={$_SESSION['set_Contr']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listEquip->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma Equipamento encontrado!</p>";
                    $this->result = false;
                }
            } else if ($_SESSION['adms_access_level_id'] == 4) {
                $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index');
                $pagination->condition($this->page, $this->limitResult);
                $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos WHERE cont_id = :cont_id","cont_id={$_SESSION['set_Contr']}");
                $this->resultPg = $pagination->getResult();

                $listEquip = new \App\adms\Models\helper\AdmsRead();
                $listEquip->fullRead("SELECT equip.id, equip.name, typ.name name_typ, emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
                FROM adms_equipamentos AS equip 
                LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
                LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
                LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id  
                WHERE equip.cont_id = :cont_id  
                ORDER BY equip.name ASC
                LIMIT :limit OFFSET :offset", "cont_id={$_SESSION['set_Contr']}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

                $this->resultBd = $listEquip->getResult();
                if ($this->resultBd) {
                    $this->result = true;
                } else {
                    $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma Equipamento encontrado!</p>";
                    $this->result = false;
                }
            }
        } else {

            $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index');
            $pagination->condition($this->page, $this->limitResult);
            $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos");
            $this->resultPg = $pagination->getResult();

            $listEquip = new \App\adms\Models\helper\AdmsRead();
            $listEquip->fullRead("SELECT equip.id, equip.name, typ.name name_typ,
            emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
            FROM adms_equipamentos AS equip 
            LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
            LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
            LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id   
            ORDER BY equip.name ASC
            LIMIT :limit OFFSET :offset", "limit={$this->limitResult}&offset={$pagination->getOffset()}");

            $this->resultBd = $listEquip->getResult();
            if ($this->resultBd) {
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhuma Equipamento encontrado!</p>";
                $this->result = false;
            }
        }
    }

    /**
     * Metodo faz a pesquisa das páginas na tabela adms_equipamentos e lista as informacoes na view
     * Recebe o paramentro "equipamento" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_equip" para que seja feita a pesquisa pelo equipamento
     * Recebe o paramentro "search_emp" para que seja feita a pesquisa pela empresa
     * @param integer|null $page
     * @param string|null $search_equip
     * @param string|null $search_emp
     * @return void
     */


    public function listSearchEquip(int $page, string|null $search_equip, string|null $search_emp): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->searchEquip = trim($search_equip);
        $this->searchEmp = trim($search_emp);


        $this->searchEquipValue = "%" . $this->searchEquip . "%";
        $this->searchEmpValue = "%" . $this->searchEmp . "%";

        if ((!empty($this->searchEquipValue)) and (!empty($this->searchEquipValue))) {
            $this->searchEquipEmp();
        } elseif ((!empty($this->searchEquip)) and (empty($this->searchEmp))) {
            $this->searchEquip();
        } elseif ((empty($this->searchEquip)) and (!empty($this->searchEmp))) {
            $this->searchEmp();
        } else {
            $this->searchEquipEmp();
        }
    }

    /**
     * Metodo pesquisar pelo equipamento e empresa
     * @return void
     */

    public function searchEquipEmp(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index', "?search_equip={$this->searchEquip}&search_emp={$this->searchEmp}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(equip.id) AS num_result, equip.name, typ.name name_typ, emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
        FROM adms_equipamentos AS equip 
        LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
        LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
        LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id   
        WHERE (equip.name LIKE :search_equip) AND (emp.nome_fantasia LIKE :search_nome_fantasia_emp )
        ORDER BY equip.name ASC", "search_equip={$this->searchEquipValue}&search_nome_fantasia_emp={$this->searchEmpValue}");
        $this->resultPg = $pagination->getResult();

        $listequip = new \App\adms\Models\helper\AdmsRead();
        $listequip->fullRead("SELECT equip.id, equip.name, typ.name name_typ,
        emp.nome_fantasia nome_fantasia_emp, sit.name name_sit
        FROM adms_equipamentos AS equip 
        LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id  
        LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
        LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id   
        WHERE (equip.name LIKE :search_equip) AND (emp.nome_fantasia LIKE :search_nome_fantasia_emp )
        ORDER BY equip.name ASC
        LIMIT :limit OFFSET :offset", "search_equip={$this->searchEquipValue}&search_nome_fantasia_emp={$this->searchEmpValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listequip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum equipamento encontradoss!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo controller
     * @return void
     */
    public function searchEquip(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index', "?search_equip={$this->searchEquip}&search_emp={$this->searchEmp}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos WHERE name LIKE :search_equip", "search_equip={$this->searchEquipValue}");
        $this->resultPg = $pagination->getResult();

        $listequip = new \App\adms\Models\helper\AdmsRead();
        $listequip->fullRead("SELECT equip.id, equip.empresa_id, equip.name, typ.name name_typ, equip.serie, modelo.name name_modelo, mar.name name_mar, 
        equip.cor, emp.nome_fantasia nome_fantasia_emp, equip.inf_adicionais, sit.name name_sit
        FROM adms_equipamentos AS equip 
        LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id 
        LEFT JOIN adms_model AS modelo ON modelo.id=equip.modelo_id 
        LEFT JOIN adms_marca AS mar ON mar.id=equip.marca_id 
        LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
        LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id  
        WHERE (equip.empresa_id= :empresa_id) and (equip.name LIKE :search_equip) ORDER BY equip.id DESC
         LIMIT :limit OFFSET :offset", "empresa_id={$_SESSION['emp_user']}&search_equip={$this->searchEquipValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");


        $this->resultBd = $listequip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum equipamento encontrado2!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisar pelo metodo
     * @return void
     */
    public function searchEmp(): void
    {
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-equip/index', "?search_equip={$this->searchEquip}&search_emp={$this->searchEmp}");
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(id) AS num_result FROM adms_equipamentos WHERE name LIKE :search_equip", "search_equip={$this->searchEquipValue}");
        $this->resultPg = $pagination->getResult();

        $listequip = new \App\adms\Models\helper\AdmsRead();
        $listequip->fullRead("SELECT equip.id, equip.name, typ.name name_typ, equip.serie, modelo.name name_modelo, mar.name name_mar, 
        equip.cor, emp.nome_fantasia nome_fantasia_emp, equip.inf_adicionais, sit.name name_sit
        FROM adms_equipamentos AS equip 
        LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id 
        LEFT JOIN adms_model AS modelo ON modelo.id=equip.modelo_id 
        LEFT JOIN adms_marca AS mar ON mar.id=equip.marca_id 
        LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
        LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id 
        WHERE empresa_id LIKE :search_nome_fantasia_emp ORDER BY id_equip DESC
         LIMIT :limit OFFSET :offset", "search_nome_fantasia_emp={$this->searchEmpValue}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listequip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p style='color: #f00'>Erro: Nenhum equipamento encontrada!</p>";
            $this->result = false;
        }
    }
}
