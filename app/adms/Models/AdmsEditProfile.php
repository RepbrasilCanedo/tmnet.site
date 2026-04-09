<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar o perfil do usuario
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditProfile
{
    private bool $result = false;
    private array|null $resultBd;
    private array|null $data;
    private array|null $dataExitVal;

    function getResult(): bool
    {
        return $this->result;
    }

    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function viewProfile(): void
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT id, name, apelido, email, telefone, user FROM adms_users
                             WHERE id=:id LIMIT :limit","id=" . $_SESSION['user_id'] . "&limit=1");

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $this->result = false;
        }
    }

    public function update(array $data): void
    {
        $this->data = $data;

        $this->dataExitVal['apelido'] = $this->data['apelido'];
        unset($this->data['apelido']);

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email'], true, $_SESSION['user_id']);

        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user'], true, $_SESSION['user_id']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valUserSingle->getResult())) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $this->data['apelido'] = $this->dataExitVal['apelido'];
        
        // DOCAN FIX: Removemos a tentativa de gravar na tabela "adms_users_final".
        // Todos os níveis de utilizadores gravam corretamente na tabela "adms_users".
        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id=" . $_SESSION['user_id']);

        if ($upUser->getResult()) {
            $_SESSION['user_name'] = $this->data['name'];
            $_SESSION['user_nickname'] = $this->data['apelido'];
            $_SESSION['user_email'] = $this->data['email'];
            $_SESSION['msg'] = "<p class='alert-success'>Perfil editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma alteração foi feita no perfil!</p>";
            $this->result = false;
        }
    }
}