<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die ("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar contratos
 */
class AddContratos
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddContrato'])) {
            unset($this->dataForm['SendAddContrato']);
            
            $createContrato = new \App\adms\Models\AdmsAddContratos();
            $createContrato->create($this->dataForm);
            
            if ($createContrato->getResult()) {
                $urlRedirect = URLADM . "list-contratos/index";
                header("Location: $urlRedirect");
                exit;
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddContrato();
            }
        } else {
            $this->viewAddContrato();
        }
    }

    private function viewAddContrato(): void
    {
        // Carrega os dados dinâmicos para os selects (Tipos de contrato e Status)
        $listSelect = new \App\adms\Models\AdmsAddContratos();
        $this->data['select'] = $listSelect->listSelect();

        $button = ['list_contratos' => ['menu_controller' => 'list-contratos', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contratos"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/addContratos", $this->data);
        $loadView->loadView();
    }
}