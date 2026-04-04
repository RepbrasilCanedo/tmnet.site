<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Modelos de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListModelo
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchModelo Recebe a modelo do equipamento*/
    private string|null $searchModelo;

    /**
     * Método listar modelos de equipamentos
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

        $this->searchModelo = filter_input(INPUT_GET, 'searchModelo', FILTER_DEFAULT);

        $listmodelo= new \App\adms\Models\AdmsListModelo();
        if (!empty($this->dataForm['SendSearchModelo'])) {
            $this->page = 1;
            $listmodelo->listSearchModelo($this->page, $this->dataForm['search_modelo']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchModelo))) {
            $listmodelo->listSearchModelo($this->page, $this->searchModelo);
            $this->data['form']['search_modelo'] = $this->searchModelo;
        } else {            
            $listmodelo->listmodelo($this->page);            
        }
        
        if ($listmodelo->getResult()) {
            $this->data['listmodelo'] = $listmodelo->getResultBd();
            $this->data['pagination'] = $listmodelo->getResultPg();
        } else {
            $this->data['listmodelo'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_modelo' => ['menu_controller' => 'add-modelo', 'menu_metodo' => 'index'],
        'view_modelo' => ['menu_controller' => 'view-modelo', 'menu_metodo' => 'index'],
        'edit_modelo' => ['menu_controller' => 'edit-modelo', 'menu_metodo' => 'index'],
        'delete_modelo' => ['menu_controller' => 'delete-modelo', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-modelo";         
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/listModelo", $this->data);
        $loadView->loadView();
    }
}
