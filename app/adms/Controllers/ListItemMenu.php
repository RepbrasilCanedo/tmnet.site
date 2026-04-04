<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListItemMenu
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome do item de menu */
    private string|null $searchName;

    /**
     * Metodo listar item de menu.
     * 
     * Instancia a MODELS responsavel em buscar os registros no banco de dados.
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

        $listItemMenu = new \App\adms\Models\AdmsListItemMenu();
        if (!empty($this->dataForm['SendSearchItemMenu'])) {
            $this->page = 1;
            $listItemMenu->listSearchItemMenu($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName))) {
            $listItemMenu->listSearchItemMenu($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {
            $listItemMenu->listItemMenu($this->page);
        }
        
        if ($listItemMenu->getResult()) {
            $this->data['listItemMenu'] = $listItemMenu->getResultBd();
            $this->data['pagination'] = $listItemMenu->getResultPg();
        } else {
            $this->data['listItemMenu'] = [];
            $this->data['pagination'] = "";
        }

        $this->data['pag'] = $this->page;

        $button = ['add_item_menu' => ['menu_controller' => 'add-item-menu', 'menu_metodo' => 'index'],
        'order_item_menu' => ['menu_controller' => 'order-item-menu', 'menu_metodo' => 'index'],
        'view_item_menu' => ['menu_controller' => 'view-item-menu', 'menu_metodo' => 'index'],
        'edit_item_menu' => ['menu_controller' => 'edit-item-menu', 'menu_metodo' => 'index'],
        'delete_item_menu' => ['menu_controller' => 'delete-item-menu', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-item-menu";         
        $loadView = new \Core\ConfigView("adms/Views/itemMenu/listItemMenu", $this->data);
        $loadView->loadView();
    }
}
