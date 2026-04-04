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

            if ($model->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Enviamos um link de recuperação para o seu e-mail!</p>";
                // Redireciona para login ou fica na mesma tela
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: E-mail não encontrado ou erro ao enviar.</p>";
            }
        }

        // Carrega a View (Crie o arquivo recoverPassword.php na pasta de login/views)
        $this->data['sidebarActive'] = "login"; 
        // Nota: Ajuste o caminho da view conforme sua estrutura de pastas pública
        (new \Core\ConfigView("adms/Views/login/recoverPassword", $this->data))->loadViewLogin();
    }
}