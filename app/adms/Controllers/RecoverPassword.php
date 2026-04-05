<?php
namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class RecoverPassword
{
    private array|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendRecover'])) {
            unset($this->dataForm['SendRecover']);
            
            $model = new \App\adms\Models\AdmsRecoverPassword();
            $model->recover($this->dataForm);

            // A Model agora é quem define o $_SESSION['msg'] com o erro real!
            if ($model->getResult()) {
                // Redireciona para a tela de Login com a mensagem de sucesso
                header("Location: " . URLADM . "login/index");
                exit;
            }
        }

        $this->data['form'] = $this->dataForm;
        $this->data['sidebarActive'] = "login"; 
        
        (new \Core\ConfigView("adms/Views/login/recoverPassword", $this->data))->loadViewLogin();
    }
}