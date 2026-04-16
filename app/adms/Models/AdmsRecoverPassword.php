<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die();
}

class AdmsRecoverPassword
{
    private bool $result = false;
    private array|null $resultBd;
    private array $data;

    function getResult(): bool { return $this->result; }

    public function recover(array $data): void
    {
        $this->data = $data;
        $this->data['email'] = trim($this->data['email']); 
        
        // 1. Verifica se o e-mail existe no banco
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, name, email FROM adms_users WHERE email = :email LIMIT 1", "email={$this->data['email']}");
        $this->resultBd = $read->getResult();

        if ($this->resultBd) {
            // 2. Gera a chave de recuperação
            $this->data['recover_password'] = bin2hex(random_bytes(32));
            $this->data['date_recover'] = date('Y-m-d H:i:s');

            // 3. Salva a chave no banco de dados
            $update = new \App\adms\Models\helper\AdmsUpdate();
            $update->exeUpdate("adms_users", [
                'recover_password' => $this->data['recover_password'],
                'date_recover' => $this->data['date_recover']
            ], "WHERE id=:id", "id={$this->resultBd[0]['id']}");

            if ($update->getResult()) {
                // 4. Se salvou no banco, envia o e-mail
                $this->sendEmail();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro interno: Falha ao gerar chave de recuperação no banco de dados.</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Este e-mail não está cadastrado no sistema!</p>";
            $this->result = false; 
        }
    }

    private function sendEmail(): void
    {
        $nome = explode(" ", $this->resultBd[0]['name'])[0];
        $emailDestino = $this->resultBd[0]['email'];
        
        $link = URLADM . "update-password/index?key=" . $this->data['recover_password'];

        $contentHtml = "Olá <b>$nome</b>,<br><br>";
        $contentHtml .= "Você solicitou a recuperação de senha no sistema <b>TMNet</b>.<br>";
        $contentHtml .= "Para criar uma nova senha, clique no link abaixo:<br><br>";
        $contentHtml .= "<a href='$link' style='background: #0044cc; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>Redefinir Minha Senha</a><br><br>";
        $contentHtml .= "Se o botão não funcionar, copie e cole este endereço no seu navegador:<br>";
        $contentHtml .= "$link<br><br>";
        $contentHtml .= "Se você não solicitou isto, por favor, ignore este e-mail.<br><br>";
        $contentHtml .= "Atenciosamente,<br>Equipe TMNet.";

        $contentText = "Olá $nome,\n\nVocê solicitou a recuperação de senha no TMNet.\n";
        $contentText .= "Acesse o link para redefinir: $link\n\n";

        $emailData = [
            'toEmail' => $emailDestino,
            'toName' => $this->resultBd[0]['name'],
            'subject' => 'Recuperação de Senha - TMNet',
            'contentHtml' => $contentHtml,
            'contentText' => $contentText
        ];

        $sendMail = new \App\adms\Models\helper\AdmsPhpMailer();
        $sendMail->sendEmail($emailData);

        if ($sendMail->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>🚀 Sucesso! Enviamos um link de recuperação para o seu e-mail (Verifique a caixa de Spam).</p>";
            $this->result = true;
        } else {
            // DOCAN FIX: Mostra o erro real do PHPMailer caso o Gmail recuse a ligação
            $erroReal = $sendMail->getErrorMsg();
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ao enviar o e-mail: {$erroReal}</p>";
            $this->result = false;
        }
    }
}