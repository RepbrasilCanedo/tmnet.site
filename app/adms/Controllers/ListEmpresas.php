<?php
namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class ListEmpresas
{
    private array|string|null $data = [];

    public function index(string|int|null $page = null): void
    {
        $page = (int) $page > 0 ? (int) $page : 1;
        $dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Captura filtros via GET (vindo da paginação)
        $filters = [];
        $filters['search_cnpj'] = filter_input(INPUT_GET, 'search_cnpj', FILTER_DEFAULT);
        $filters['search_razao'] = filter_input(INPUT_GET, 'search_razao', FILTER_DEFAULT);
        $filters['search_fantasia'] = filter_input(INPUT_GET, 'search_fantasia', FILTER_DEFAULT);

        // Se for uma nova busca via POST
        if (!empty($dataForm['SendSearchEmpresa'])) {
            $filters = $dataForm;
            $page = 1;
        }

        $listModel = new \App\adms\Models\AdmsListEmpresas();
        $listModel->listEmpresas($page, $filters);

        $this->data['listEmpresas'] = $listModel->getResultBd() ?? [];
        $this->data['pagination'] = $listModel->getResultPg();
        $this->data['form'] = $filters;

        // Permissões de botões
        $button = [
            'add_empresas' => ['menu_controller' => 'add-empresas', 'menu_metodo' => 'index'],
            'view_empresas' => ['menu_controller' => 'view-empresas', 'menu_metodo' => 'index'],
            'edit_empresas' => ['menu_controller' => 'edit-empresas', 'menu_metodo' => 'index'],
            'delete_empresas' => ['menu_controller' => 'delete-empresas', 'menu_metodo' => 'index']
        ];
        
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission($button);
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu(); 
        $this->data['sidebarActive'] = "list-empresas"; 
        
        (new \Core\ConfigView("adms/Views/empresas/listEmpresas", $this->data))->loadView();
    }
}