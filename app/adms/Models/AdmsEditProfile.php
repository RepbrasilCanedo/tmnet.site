<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditProfile
{
    private bool $result = false;
    private array|null $resultBd;
    private array|null $data;
    private array|null $dataExitVal;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewProfile(): void
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        // DOCAN FIX: Adicionada a data_nascimento na busca!
        $viewUser->fullRead("SELECT id, name, apelido, email, telefone, user, data_nascimento, rg, cep, endereco, numero, bairro, cidade, estado, escolaridade, instagram 
                             FROM adms_users WHERE id=:id LIMIT :limit", "id=" . $_SESSION['user_id'] . "&limit=1");

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

        // Campos opcionais que não barram a validação
        $this->dataExitVal['apelido'] = $this->data['apelido'] ?? null;
        $this->dataExitVal['instagram'] = $this->data['instagram'] ?? null;
        
        unset($this->data['apelido'], $this->data['instagram']);

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
        
        // Devolve os campos opcionais
        $this->data['apelido'] = $this->dataExitVal['apelido'];
        $this->data['instagram'] = $this->dataExitVal['instagram'];
        
        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id=" . $_SESSION['user_id']);

        if ($upUser->getResult()) {
            $_SESSION['user_name'] = $this->data['name'];
            $_SESSION['user_nickname'] = $this->data['apelido'];
            $_SESSION['user_email'] = $this->data['email'];
            $_SESSION['msg'] = "<p class='alert-success'>🚀 Perfil atualizado com sucesso! O seu Passaporte de Atleta está em dia.</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma alteração foi feita no perfil.</p>";
            $this->result = false;
        }
    }
}