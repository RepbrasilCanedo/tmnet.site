<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsNewUser
{
    private array|null $data;
    private array|null $resultBd;
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function create(array $data)
    {
        $this->data = $data;
        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        
        if ($valEmptyField->getResult()) {
            $this->verifUser();
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Preencha todos os campos!</div>";
            $this->result = false;
        }
    }

    private function verifUser(): void
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        // Verifica no administrativo e usuário final nivel 14
        $viewUser->fullRead("SELECT name, empresa_id, cliente_id FROM adms_users_final WHERE user = :user LIMIT 1", "user={$this->data['email']}");
        $this->resultBd = $viewUser->getResult();

        if ($this->resultBd) {
            $this->add();
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Usuário não identificado no sistema!</div>";
            $this->result = false;
        }
    }   
    
    private function add(): void
    {
        date_default_timezone_set('America/Bahia');

        $this->data['empresa_id'] = $this->resultBd[0]['empresa_id'];
        $this->data['cliente_id'] = $this->resultBd[0]['cliente_id'];            
        $this->data['nome'] = $this->resultBd[0]['name'];
        $this->data['assunto'] = 'Solicitação de Nova Senha';
        $this->data['dia'] = date("Y-m-d H:i:s");
        $this->data['status'] = 'Pendente';
        $this->data['mensagem'] = "O usuário {$this->data['email']} solicitou nova senha. Contato: {$this->data['tel']}";
        $this->data['created'] = date("Y-m-d H:i:s");

        $createMsg = new \App\adms\Models\helper\AdmsCreate();
        $createMsg->exeCreate("sts_contacts_msgs", $this->data);

        if ($createMsg->getResult()) {
            $_SESSION['msg'] = "<div class='alert-success'>Solicitação enviada! Aguarde nosso contato.</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Falha ao enviar solicitação técnica.</div>";
            $this->result = false;
        }
    }
}