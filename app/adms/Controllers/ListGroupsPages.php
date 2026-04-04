<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar groups de páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListGroupsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome da grupo de página */
    private string|null $searchName;

    /**
     * Método listar grupos de páginas.
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

        $listGroupsPages = new \App\adms\Models\AdmsListGroupsPages();
        if (!empty($this->dataForm['SendSearchGroupsPages'])) {
            $this->page = 1;
            $listGroupsPages->listSearchGroupsPages($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName))) {
            $listGroupsPages->listSearchGroupsPages($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {
            $listGroupsPages->listGroupsPages($this->page);
        }
        
        if ($listGroupsPages->getResult()) {
            $this->data['listGroupsPages'] = $listGroupsPages->getResultBd();
            $this->data['pagination'] = $listGroupsPages->getResultPg();
        } else {
            $this->data['listGroupsPages'] = [];
        }

        $button = ['add_groups_pages' => ['menu_controller' => 'add-groups-pages', 'menu_metodo' => 'index'],
        'order_groups_pages' => ['menu_controller' => 'order-groups-pages', 'menu_metodo' => 'index'],
        'view_groups_pages' => ['menu_controller' => 'view-groups-pages', 'menu_metodo' => 'index'],
        'edit_groups_pages' => ['menu_controller' => 'edit-groups-pages', 'menu_metodo' => 'index'],
        'delete_groups_pages' => ['menu_controller' => 'delete-groups-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['pag'] = $this->page;
        $this->data['sidebarActive'] = "list-groups-pages"; 
        $loadView = new \Core\ConfigView("adms/Views/groupsPages/listGroupsPages", $this->data);
        $loadView->loadView();
    }
}
