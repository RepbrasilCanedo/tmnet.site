<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die();
}

class NewUser
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT); 

        if(!empty($this->dataForm['SendNewUser'])){
            unset($this->dataForm['SendNewUser']);
            
            $createNewUser = new \App\adms\Models\AdmsNewUser();
            $createNewUser->create($this->dataForm);

            if($createNewUser->getResult()){
                // Mantém a mensagem de sucesso para ser exibida na tela de login
                header("Location: " . URLADM . "login/index");
                exit();
            } else {
                $this->data['form'] = $this->dataForm;
            }
        }
        $this->viewNewUser();
    }

    private function viewNewUser(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/newUser", $this->data);
        $loadView->loadViewLogin();
    }
}