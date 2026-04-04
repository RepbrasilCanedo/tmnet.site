<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar situação páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListSitsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome da situacao de página */
    private string|null $searchName;

    /**
     * Método listar situação páginas.
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

        $listSitsPages = new \App\adms\Models\AdmsListSitsPages();
        if (!empty($this->dataForm['SendSearchSitPage'])) {
            $this->page = 1;
            $listSitsPages->listSearchSitsPages($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchName))) {
            $listSitsPages->listSearchSitsPages($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {
            $listSitsPages->listSitsPages($this->page);
        }
        
        if ($listSitsPages->getResult()) {
            $this->data['listSitsPages'] = $listSitsPages->getResultBd();
            $this->data['pagination'] = $listSitsPages->getResultPg();
        } else {
            $this->data['listSitsPages'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_sits_pages' => ['menu_controller' => 'add-sits-pages', 'menu_metodo' => 'index'],
        'view_sits_pages' => ['menu_controller' => 'view-sits-pages', 'menu_metodo' => 'index'],
        'edit_sits_pages' => ['menu_controller' => 'edit-sits-pages', 'menu_metodo' => 'index'],
        'delete_sits_pages' => ['menu_controller' => 'delete-sits-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-sits-pages"; 
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/listSitPages", $this->data);
        $loadView->loadView();
    }
}
