<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die();
}

/**
 * Solicitar novo link de senha e disparar e-mail
 */
class AdmsRecoverPassword
{
    private bool $result = false;
    private array|null $resultBd;
    private array $data;

    function getResult(): bool { return $this->result; }

    public function recover(array $data): void
    {
        $this->data = $data;
        
        // 1. Verifica se o e-mail existe no banco
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, name, email FROM adms_users WHERE email = :email LIMIT 1", "email={$this->data['email']}");
        $this->resultBd = $read->getResult();

        if ($this->resultBd) {
            // 2. Gera a chave de recuperação (Hash seguro)
            //$this->data['recover_password'] = password_hash($this->resultBd[0]['id'] . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
            $this->data['recover_password'] = bin2hex(random_bytes(32));
            $this->data['date_recover'] = date('Y-m-d H:i:s');

            // 3. Salva a chave no banco de dados
            $update = new \App\adms\Models\helper\AdmsUpdate();
            $update->exeUpdate("adms_users", [
                'recover_password' => $this->data['recover_password'],
                'date_recover' => $this->data['date_recover']
            ], "WHERE id=:id", "id={$this->resultBd[0]['id']}");

            if ($update->getResult()) {
                // 4. Se salvou no banco, envia o e-mail usando sua Helper
                $this->sendEmail();
            } else {
                $this->result = false;
            }
        } else {
            $this->result = false; // E-mail não encontrado
        }
    }

    private function sendEmail(): void
    {
        $nome = explode(" ", $this->resultBd[0]['name'])[0];
        $emailDestino = $this->resultBd[0]['email'];
        
        // Monta o Link (Certifique-se que URLADM está correta no Config.php)
        $link = URLADM . "update-password/index?key=" . $this->data['recover_password'];

        // Conteúdo HTML do E-mail
        $contentHtml = "Olá <b>$nome</b>,<br><br>";
        $contentHtml .= "Você solicitou a recuperação de senha no sistema <b>DocNet</b>.<br>";
        $contentHtml .= "Para criar uma nova senha, clique no link abaixo:<br><br>";
        $contentHtml .= "<a href='$link'>Clique aqui para redefinir sua senha</a><br><br>";
        $contentHtml .= "Se o link não abrir, copie e cole este endereço no navegador:<br>";
        $contentHtml .= "$link<br><br>";
        $contentHtml .= "Se você não solicitou isso, por favor, ignore este e-mail.<br><br>";
        $contentHtml .= "Atenciosamente,<br>Equipe DocNet.";

        // Conteúdo Texto Puro (Fallback)
        $contentText = "Olá $nome,\n\nVocê solicitou a recuperação de senha no DocNet.\n";
        $contentText .= "Acesse o link para redefinir: $link\n\n";

        // Prepara os dados para a sua Helper AdmsPhpMailer
        $emailData = [
            'toEmail' => $emailDestino,
            'toName' => $this->resultBd[0]['name'],
            'subject' => 'Recuperar Senha - DocNet',
            'contentHtml' => $contentHtml,
            'contentText' => $contentText
        ];

        // Instancia a Helper que você me enviou
        $sendMail = new \App\adms\Models\helper\AdmsPhpMailer();
        $sendMail->sendEmail($emailData);

        if ($sendMail->getResult()) {
            $this->result = true;
        } else {
            // Se o e-mail falhar, consideramos erro (para avisar o usuário)
            // Opcional: Você pode logar o erro aqui se quiser
            $this->result = false;
        }
    }
}