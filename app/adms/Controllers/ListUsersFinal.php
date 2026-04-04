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
class ListUsersFinal
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

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchEmail;

    /** @var string|null $searchEmail Recebe o email do usuario */
    private string|null $searchContrato;

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
        $this->searchEmail = filter_input(INPUT_GET, 'search_email', FILTER_DEFAULT);

        $listUsers = new \App\adms\Models\AdmsListUsersFinal();
        if (!empty($this->dataForm['SendSearchUser'])) {
            $this->page = 1;
            $listUsers->listSearchUsers($this->page, $this->dataForm['search_name'], $this->dataForm['search_empresa'], $this->dataForm['search_email']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName)) or (!empty($this->searchEmpresa) or (!empty($this->searchEmail)))) {
            $listUsers->listSearchUsers($this->page, $this->searchName, $this->searchEmpresa, $this->searchEmail);
            $this->data['form']['search_name'] = $this->searchName;
            $this->data['form']['search_empresa'] = $this->searchEmpresa;
            $this->data['form']['search_email'] = $this->searchEmail;
        } else {            
            $listUsers->listUsers($this->page);            
        }

        if ($listUsers->getResult()) {
            $this->data['listUsersFinal'] = $listUsers->getResultBd();
            $this->data['pagination'] = $listUsers->getResultPg();
        } else {
            $this->data['listUsersFinal'] = [];
            $this->data['pagination'] = "";
        }

        $button = [
            'add_users_final' => ['menu_controller' => 'add-users-final', 'menu_metodo' => 'index'],
            'view_users_final' => ['menu_controller' => 'view-users-final', 'menu_metodo' => 'index'],
            'edit_users_final' => ['menu_controller' => 'edit-users-final', 'menu_metodo' => 'index'],
            'delete_users_final' => ['menu_controller' => 'delete-users-final', 'menu_metodo' => 'index']
        ];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $listSelect = new \App\adms\Models\AdmsListUsersFinal();
        $this->data['select'] = $listSelect->listSelect();

        $this->data['sidebarActive'] = "list-users-final";

        $loadView = new \Core\ConfigView("adms/Views/users/listUsersFinal", $this->data);
        $loadView->loadView();
    }
}
