<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar contratos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListContratos
{
    private array|string|null $data = [];
    private string|int|null $page;
    private array|null $dataForm;
    private string|null $searchName;

    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Recupera a pesquisa da URL (para a paginação funcionar com os filtros)
        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);

        $listContratosModel = new \App\adms\Models\AdmsListContratos();

        // Lógica de prioridade: Formulário (POST) > Paginação (GET) > Listagem Padrão
        if (!empty($this->dataForm['listSearchContratos'])) {
            $this->page = 1;
            $listContratosModel->listSearchContratos($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchName)) {
            $listContratosModel->listSearchContratos($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {            
            $listContratosModel->listContratos($this->page);            
        }
        
        if ($listContratosModel->getResult()) {
            $this->data['listContratos'] = $listContratosModel->getResultBd();
            $this->data['pagination'] = $listContratosModel->getResultPg();
        } else {
            $this->data['listContratos'] = [];
            $this->data['pagination'] = "";
        }

        // Configuração das permissões de botões para os Contratos
        $button = [
            'add_contratos' => ['menu_controller' => 'add-contratos', 'menu_metodo' => 'index'],
            'view_contratos' => ['menu_controller' => 'view-contratos', 'menu_metodo' => 'index'],
            'edit_contratos' => ['menu_controller' => 'edit-contratos', 'menu_metodo' => 'index'],
            'delete_contratos' => ['menu_controller' => 'delete-contratos', 'menu_metodo' => 'index']
        ];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-contratos";        
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/listContratos", $this->data);
        $loadView->loadView();
    }
}