<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Marcas de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListMarca
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchMarca Recebe a marca do equipamento*/
    private string|null $searchMarca;

    /**
     * Método listar marcas de equipamentos
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

        $this->searchMarca = filter_input(INPUT_GET, 'searchMarca', FILTER_DEFAULT);

        $listmarca= new \App\adms\Models\AdmsListMarca();
        if (!empty($this->dataForm['SendSearchMarca'])) {
            $this->page = 1;
            $listmarca->listSearchMarca($this->page, $this->dataForm['search_marca']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchMarca))) {
            $listmarca->listSearchMarca($this->page, $this->searchMarca);
            $this->data['form']['search_marca'] = $this->searchMarca;
        } else {            
            $listmarca->listmarca($this->page);            
        }
        
        if ($listmarca->getResult()) {
            $this->data['listmarca'] = $listmarca->getResultBd();
            $this->data['pagination'] = $listmarca->getResultPg();
        } else {
            $this->data['listmarca'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_marca' => ['menu_controller' => 'add-marca', 'menu_metodo' => 'index'],
        'view_marca' => ['menu_controller' => 'view-marca', 'menu_metodo' => 'index'],
        'edit_marca' => ['menu_controller' => 'edit-marca', 'menu_metodo' => 'index'],
        'delete_marca' => ['menu_controller' => 'delete-marca', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-marca";         
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/listMarca", $this->data);
        $loadView->loadView();
    }
}
