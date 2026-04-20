<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AddUsers
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT); 

        if(!empty($this->dataForm['SendAddUser'])){
            unset($this->dataForm['SendAddUser']);
            $createUser = new \App\adms\Models\AdmsAddUsers();
            $createUser->create($this->dataForm);
            
            if($createUser->getResult()){
                $urlRedirect = URLADM . "list-users/index";
                header("Location: $urlRedirect");
                exit; // DOCAN FIX: Evitar erro de loop
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddUser();
            }   
        }else{
            $this->viewAddUser();
        }  
    }

    private function viewAddUser(): void
    {
        $button = ['list_users' => ['menu_controller' => 'list-users', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddUsers();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-users"; 
        
        $loadView = new \Core\ConfigView("adms/Views/users/addUser", $this->data);
        $loadView->loadView();
    }
}