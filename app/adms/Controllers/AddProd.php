<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar Produtos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddProd
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);        

        if(!empty($this->dataForm['SendAddProd'])){
            unset($this->dataForm['SendAddProd']);
            $createProd = new \App\adms\Models\AdmsAddProd();
            $createProd->create($this->dataForm);
            
            if($createProd->getResult()){
                $urlRedirect = URLADM . "list-prod/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddProd();
            }   
        }else{
            $this->viewAddProd();
        }  
    }

    private function viewAddProd(): void
    {
        $button = ['list_prod' => ['menu_controller' => 'list-prod', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddProd();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-prod"; 
        
        $loadView = new \Core\ConfigView("adms/Views/produtos/addProd", $this->data);
        $loadView->loadView();
    }
}