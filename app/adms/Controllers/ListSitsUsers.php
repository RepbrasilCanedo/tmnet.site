<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar situação usuário
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListSitsUsers
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome da situacao */
    private string|null $searchName;

    /**
     * Método listar situação usuário.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);

        $listSitsUsers = new \App\adms\Models\AdmsListSitsUsers();
        if (!empty($this->dataForm['SendSearchSitUser'])) {
            $this->page = 1;
            $listSitsUsers->listSearchSitsUsers($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName))) {
            $listSitsUsers->listSearchSitsUsers($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {
            $listSitsUsers->listSitsUsers($this->page);
        }
        
        if ($listSitsUsers->getResult()) {
            $this->data['listSitsUsers'] = $listSitsUsers->getResultBd();
            $this->data['pagination'] = $listSitsUsers->getResultPg();
        } else {
            $this->data['listSitsUsers'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_sits_users' => ['menu_controller' => 'add-sits-users', 'menu_metodo' => 'index'],
        'view_sits_users' => ['menu_controller' => 'view-sits-users', 'menu_metodo' => 'index'],
        'edit_sits_users' => ['menu_controller' => 'edit-sits-users', 'menu_metodo' => 'index'],
        'delete_sits_users' => ['menu_controller' => 'delete-sits-users', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-sits-users"; 
        $loadView = new \Core\ConfigView("adms/Views/sitsUser/listSitUser", $this->data);
        $loadView->loadView();
    }
}
