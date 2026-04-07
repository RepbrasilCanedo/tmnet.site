<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller login.
 * @author Daniel Canedo - docan2006@gmail.com
 */
class Login
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    public function index(): void
    {

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);        

        if(!empty($this->dataForm['SendLogin'])){
            $valLogin = new \App\adms\Models\AdmsLogin();
            $valLogin->login($this->dataForm);
            
            if($valLogin->getResult()){
                $urlRedirect = URLADM . "dashboard/index";
                header("Location: $urlRedirect");
                exit(); // DOCAN FIX: É obrigatório dar um exit() após um redirecionamento de Login para segurança!
            }else{
                $this->data['form'] = $this->dataForm;
            }            
        }

        $loadView = new \Core\ConfigView("adms/Views/login/login", $this->data);
        $loadView->loadViewLogin();
    }
}