<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar nivel de acesso
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListAccessLevels
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome do nível de acesso */
    private string|null $searchName;

    /** @var string|null $searchName Recebe o nome do nível de acesso */
    private string|null $searchEmail;

    /**
     * Método listar niveis de acesso.
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

        $listAccessLevels = new \App\adms\Models\AdmsListAccessLevels();
        if (!empty($this->dataForm['SendSearchAccessLevels'])) {
            $this->page = 1;
            $listAccessLevels->listSearchAccessLevels($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName)) or (!empty($this->searchEmail))) {
            $listAccessLevels->listSearchAccessLevels($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {
            $listAccessLevels->listAccessLevels($this->page);
        }

        if ($listAccessLevels->getResult()) {
            $this->data['listAccessLevels'] = $listAccessLevels->getResultBd();
            $this->data['pagination'] = $listAccessLevels->getResultPg();
        } else {
            $this->data['listAccessLevels'] = [];
            $this->data['pagination'] = "";
        }

        $this->data['pag'] = $this->page;

        $button = ['add_access_levels' => ['menu_controller' => 'add-access-levels', 'menu_metodo' => 'index'],
        'sync_pages_levels' => ['menu_controller' => 'sync-pages-levels', 'menu_metodo' => 'index'],
        'order_access_levels' => ['menu_controller' => 'order-access-levels', 'menu_metodo' => 'index'],
        'list_permission' => ['menu_controller' => 'list-permission', 'menu_metodo' => 'index'],
        'view_access_levels' => ['menu_controller' => 'view-access-levels', 'menu_metodo' => 'index'],
        'edit_access_levels' => ['menu_controller' => 'edit-access-levels', 'menu_metodo' => 'index'],
        'delete_access_levels' => ['menu_controller' => 'delete-access-levels', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-access-levels";         
        $loadView = new \Core\ConfigView("adms/Views/accessLevels/listAccessLevels", $this->data);
        $loadView->loadView();
    }
}
