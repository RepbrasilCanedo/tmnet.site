<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditContratos
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($this->dataForm['SendEditContrato'])) {
                unset($this->dataForm['SendEditContrato']);
                
                $editContrato = new \App\adms\Models\AdmsEditContratos();
                $editContrato->update($this->dataForm);
                
                if ($editContrato->getResult()) {
                    $urlRedirect = URLADM . "list-contratos/index";
                    header("Location: $urlRedirect");
                    exit;
                } else {
                    $this->data['form'] = $this->dataForm;
                    $this->viewEditContrato();
                }
            } else {
                $viewContrato = new \App\adms\Models\AdmsEditContratos();
                $viewContrato->viewContrato($this->id);
                
                if ($viewContrato->getResult()) {
                    $this->data['form'] = $viewContrato->getResultBd()[0];
                    $this->viewEditContrato();
                } else {
                    $urlRedirect = URLADM . "list-contratos/index";
                    header("Location: $urlRedirect");
                    exit;
                }
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado!</p>";
            $urlRedirect = URLADM . "list-contratos/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    private function viewEditContrato(): void
    {
        $listSelect = new \App\adms\Models\AdmsEditContratos();
        $this->data['select'] = $listSelect->listSelect();

        $button = [
            'list_contratos' => ['menu_controller' => 'list-contratos', 'menu_metodo' => 'index'],
            'view_contratos' => ['menu_controller' => 'view-contratos', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contratos"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/editContratos", $this->data);
        $loadView->loadView();
    }
}