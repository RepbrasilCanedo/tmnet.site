<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die ("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar tipos de contratos
 */
class AddTipoContr
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddTipoContr'])) {
            unset($this->dataForm['SendAddTipoContr']);
            
            $createTipoContr = new \App\adms\Models\AdmsAddTipoContr();
            $createTipoContr->create($this->dataForm);
            
            if ($createTipoContr->getResult()) {
                $urlRedirect = URLADM . "list-tipo-contr/index";
                header("Location: $urlRedirect");
                exit; // Segurança: Interrompe o script após o redirecionamento
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewTipoContr();
            }
        } else {
            $this->viewTipoContr();
        }
    }

    private function viewTipoContr(): void
    {
        $button = ['list_tipo_contr' => ['menu_controller' => 'list-tipo-contr', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-tipo-contr"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/addTipoContr", $this->data);
        $loadView->loadView();
    }
}