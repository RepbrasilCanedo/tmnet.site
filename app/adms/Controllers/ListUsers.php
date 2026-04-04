<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar usuários
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListUsers
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchName Recebe o nome do usuario */
    private string|null $searchName;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmpresa;

    /**
     * Método listar usuários.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null)
    {
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);
        $this->searchEmpresa = filter_input(INPUT_GET, 'search_empresa', FILTER_DEFAULT);

        $listUsers = new \App\adms\Models\AdmsListUsers();

        if($_SESSION['adms_access_level_id'] <= 2){
            if (!empty($this->dataForm['SendSearchUser'])) {
                $this->page = 1;
                $listUsers->listSearchUsers($this->page, $this->dataForm['search_name'], $this->dataForm['search_empresa']);
                $this->data['form'] = $this->dataForm;
            } elseif ((!empty($this->searchName)) or (!empty($this->searchEmpresa))) {
                $listUsers->listSearchUsers($this->page, $this->searchName, $this->searchEmpresa);
                $this->data['form']['search_name'] = $this->searchName;
                $this->data['form']['search_empresa'] = $this->searchEmpresa;
            } else {            
                $listUsers->listUsers($this->page);            
            }
        }else {
            if (!empty($this->dataForm['SendSearchUser'])) {
                $this->page = 1;
                $listUsers->listSearchUsers($this->page, $this->dataForm['search_name'], $this->dataForm['search_empresa']);
                $this->data['form'] = $this->dataForm;
            } elseif ((!empty($this->searchName)) or (!empty($this->searchEmpresa))) {
                $listUsers->listSearchUsers($this->page, $this->searchName, $this->searchEmpresa);
                $this->data['form']['search_name'] = $this->searchName;
                $this->data['form']['search_empresa'] = $this->searchEmpresa;
            } else {            
                $listUsers->listUsers($this->page);            
            }
        }
        

        if ($listUsers->getResult()) {
            $this->data['listUsers'] = $listUsers->getResultBd();
            $this->data['pagination'] = $listUsers->getResultPg();
        } else {
            $this->data['listUsers'] = [];
            $this->data['pagination'] = "";
        }

        $button = [
            'add_users' => ['menu_controller' => 'add-users', 'menu_metodo' => 'index'],
            'view_users' => ['menu_controller' => 'view-users', 'menu_metodo' => 'index'],
            'edit_users' => ['menu_controller' => 'edit-users', 'menu_metodo' => 'index'],
            'delete_users' => ['menu_controller' => 'delete-users', 'menu_metodo' => 'index']
        ];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['sidebarActive'] = "list-users";

        $loadView = new \Core\ConfigView("adms/Views/users/listUsers", $this->data);
        $loadView->loadView();
    }
}
