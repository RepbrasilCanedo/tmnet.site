<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditUsers
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {   
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditUser']))) {
            $this->id = (int) $id;

            $viewUser = new \App\adms\Models\AdmsEditUsers();
            $viewUser->viewUser($this->id);

            if ($viewUser->getResult()) {
                $this->data['form'] = $viewUser->getResultBd();
                $this->viewEditUser();
            } else {
                $urlRedirect = URLADM . "list-users/index";
                header("Location: $urlRedirect");
                exit; // DOCAN FIX
            } 
        
        } else {
            $this->editUser();
        }
    }

    private function viewEditUser(): void
    {
        $button = [
            'list_users' => ['menu_controller' => 'list-users', 'menu_metodo' => 'index'],
            'view_users' => ['menu_controller' => 'view-users', 'menu_metodo' => 'index'],
            'del_clie_user' => ['menu_controller' => 'del-clie-user', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsEditUsers();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-users"; 
        $loadView = new \Core\ConfigView("adms/Views/users/editUser", $this->data);
        $loadView->loadView();
    }

    private function editUser(): void
    {
        if (!empty($this->dataForm['SendEditUser'])) {
            unset($this->dataForm['SendEditUser']);

            $editUser = new \App\adms\Models\AdmsEditUsers();
            $editUser->update($this->dataForm);
            
            if($editUser->getResult()){
                $urlRedirect = URLADM . "view-users/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
                exit; // DOCAN FIX
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditUser();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $urlRedirect = URLADM . "list-users/index";
            header("Location: $urlRedirect");
            exit; // DOCAN FIX
        }
    }
}