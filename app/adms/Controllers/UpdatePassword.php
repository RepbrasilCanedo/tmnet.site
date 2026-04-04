<?php
namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class UpdatePassword
{
    private array|null $data = [];
    private array|null $dataForm;
    private string|null $key;

    public function index(): void
    {
        // Pega a chave da URL (GET)
        $this->key = filter_input(INPUT_GET, 'key', FILTER_DEFAULT);
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $model = new \App\adms\Models\AdmsUpdatePassword();

        // Se o usuário clicou no botão "Salvar Nova Senha"
        if (!empty($this->dataForm['SendUpPass'])) {
            unset($this->dataForm['SendUpPass']);
            $this->dataForm['key'] = $this->key; // Passa a chave para validar novamente

            $model->update($this->dataForm);

            if ($model->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Senha atualizada com sucesso! Faça login.</p>";
                header("Location: " . URLADM . "login/index");
                return;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Link inválido ou expirado.</p>";
            }
        }

        // Valida se o link (chave) é válido ao abrir a página
        if ($model->valKey($this->key)) {
            // Carrega a View de Nova Senha
            (new \Core\ConfigView("adms/Views/login/updatePassword", $this->data))->loadViewLogin();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Link inválido ou expirado.</p>";
            header("Location: " . URLADM . "login/index");
        }
    }
}