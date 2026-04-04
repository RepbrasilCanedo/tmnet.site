<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchName Recebe o nome do controller */
    private string|null $searchController;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchMetodo;

    /**
     * Método listar páginas.
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

        $this->searchController = filter_input(INPUT_GET, 'search_controller', FILTER_DEFAULT);
        $this->searchMetodo = filter_input(INPUT_GET, 'search_metodo', FILTER_DEFAULT);

        $listPages = new \App\adms\Models\AdmsListPages();
        if (!empty($this->dataForm['SendSearchPages'])) {
            $this->page = 1;
            $listPages->listSearchPages($this->page, $this->dataForm['search_controller'], $this->dataForm['search_metodo']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchController)) or (!empty($this->searchMetodo))) {
            $listPages->listSearchPages($this->page, $this->searchController, $this->searchMetodo);
            $this->data['form']['search_controller'] = $this->searchController;
            $this->data['form']['search_metodo'] = $this->searchMetodo;
        } else {            
            $listPages->listPages($this->page);            
        }
        
        if ($listPages->getResult()) {
            $this->data['listPages'] = $listPages->getResultBd();
            $this->data['pagination'] = $listPages->getResultPg();
        } else {
            $this->data['listPages'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_pages' => ['menu_controller' => 'add-pages', 'menu_metodo' => 'index'],
        'sync_pages_levels' => ['menu_controller' => 'sync-pages-levels', 'menu_metodo' => 'index'],
        'view_pages' => ['menu_controller' => 'view-pages', 'menu_metodo' => 'index'],
        'edit_pages' => ['menu_controller' => 'edit-pages', 'menu_metodo' => 'index'],
        'delete_pages' => ['menu_controller' => 'delete-pages', 'menu_metodo' => 'index']];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['pag'] = $this->page;
        $this->data['sidebarActive'] = "list-pages"; 
        $loadView = new \Core\ConfigView("adms/Views/pages/listPages", $this->data);
        $loadView->loadView();
    }
}
