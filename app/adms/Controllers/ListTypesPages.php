<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar tipos de páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListTypesPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchType Recebe o tipo de página */
    private string|null $searchType;

    /**
     * Método listar tipos de páginas.
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

        $this->searchType = filter_input(INPUT_GET, 'search_type', FILTER_DEFAULT);

        $listTypesPages = new \App\adms\Models\AdmsListTypesPages();
        if (!empty($this->dataForm['SendSearchTypePage'])) {
            $this->page = 1;
            $listTypesPages->listSearchTypesPages($this->page, $this->dataForm['search_type']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchType))) {
            $listTypesPages->listSearchTypesPages($this->page, $this->searchType);
            $this->data['form']['search_type'] = $this->searchType;
        } else {
            $listTypesPages->listTypesPages($this->page);
        }
        
        if ($listTypesPages->getResult()) {
            $this->data['listTypesPages'] = $listTypesPages->getResultBd();
            $this->data['pagination'] = $listTypesPages->getResultPg();
        } else {
            $this->data['listTypesPages'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_types_pages' => ['menu_controller' => 'add-types-pages', 'menu_metodo' => 'index'],
        'order_types_pages' => ['menu_controller' => 'order-types-pages', 'menu_metodo' => 'index'],
        'view_types_pages' => ['menu_controller' => 'view-types-pages', 'menu_metodo' => 'index'],
        'edit_types_pages' => ['menu_controller' => 'edit-types-pages', 'menu_metodo' => 'index'],
        'delete_types_pages' => ['menu_controller' => 'delete-types-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['pag'] = $this->page;
        $this->data['sidebarActive'] = "list-types-pages"; 
        $loadView = new \Core\ConfigView("adms/Views/typesPages/listTypesPages", $this->data);
        $loadView->loadView();
    }
}
