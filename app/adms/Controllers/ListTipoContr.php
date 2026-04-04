<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar tipos de contratos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListTipoContr
{
    private array|string|null $data = [];
    private string|int|null $page;
    private array|null $dataForm;
    private string|null $searchName;

    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Pega a pesquisa da URL (útil para quando o usuário clica na paginação)
        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);

        $listContrModel = new \App\adms\Models\AdmsListTipoContr();

        // Lógica de prioridade: Formulário submetido (POST) > Paginação (GET) > Listagem Padrão
        if (!empty($this->dataForm['listSearchCTipoContr'])) {
            $this->page = 1; // Reseta a página ao fazer nova pesquisa
            $listContrModel->listSearchCTipoContr($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } elseif (!empty($this->searchName)) {
            $listContrModel->listSearchCTipoContr($this->page, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        } else {            
            $listContrModel->listTipoContr($this->page);            
        }
        
        // Preenche os dados para a View
        if ($listContrModel->getResult()) {
            $this->data['listTipoContr'] = $listContrModel->getResultBd();
            $this->data['pagination'] = $listContrModel->getResultPg();
        } else {
            $this->data['listTipoContr'] = [];
            $this->data['pagination'] = "";
        }

        // Permissões de botões
        $button = [
            'add_tipo_contr' => ['menu_controller' => 'add-tipo-contr', 'menu_metodo' => 'index'],
            'view_tipo_contr' => ['menu_controller' => 'view-tipo-contr', 'menu_metodo' => 'index'],
            'edit_tipo_contr' => ['menu_controller' => 'edit-tipo-contr', 'menu_metodo' => 'index'],
            'delete_tipo_contr' => ['menu_controller' => 'delete-tipo-contr', 'menu_metodo' => 'index']
        ];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-tipo-contr";        
        
        // Carrega a View
        $loadView = new \Core\ConfigView("adms/Views/contratos/listTipoContr", $this->data);
        $loadView->loadView();
    }
}