<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AddCategoria
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT); 

        if(!empty($this->dataForm['SendAddCategoria'])){
            unset($this->dataForm['SendAddCategoria']);
            
            $createCat = new \App\adms\Models\AdmsAddCategoria();
            $createCat->create($this->dataForm);
            
            if($createCat->getResult()){
                // Após cadastrar, redireciona para a lista (vamos criá-la a seguir)
                $urlRedirect = URLADM . "list-categorias/index";
                header("Location: $urlRedirect");
                exit;
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddCategoria();
            }   
        }else{
            $this->viewAddCategoria();
        }  
    }

    private function viewAddCategoria(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-categorias"; 
        
        $loadView = new \Core\ConfigView("adms/Views/categoria/addCategoria", $this->data);
        $loadView->loadView();
    }
}