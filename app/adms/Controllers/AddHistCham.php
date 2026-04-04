<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AddHistCham
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);   
           
        
        if(!empty($this->dataForm['SendAddHistCham'])){            
            unset($this->dataForm['SendAddHistCham']);
            
            $createHistCham = new \App\adms\Models\AdmsAddHistCham();
            $createHistCham->create($this->dataForm);

            if($createHistCham->getResult()){
                // Redireciona para o chamado de origem gravado na sessão
                $urlRedirect = URLADM . "view-cham/index/" . $_SESSION['set_cham'];
                header("Location: $urlRedirect");
                exit();
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddHistCham();
            }   
        } else {
            $this->viewAddHistCham();
        }   
    }

    private function viewAddHistCham(): void
    {
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu(); 
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'edit_cham' => ['menu_controller' => 'edit-cham', 'menu_metodo' => 'index']
        ]);

        $this->data['sidebarActive'] = "edit-cham"; 
        (new \Core\ConfigView("adms/Views/chamados/addHistCham", $this->data))->loadView();
    }
}