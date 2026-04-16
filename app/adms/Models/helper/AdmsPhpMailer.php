<?php

namespace App\adms\Models\helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsPhpMailer
{
    private bool $result = false;
    private array $data;
    private string $errorMsg = '';

    function getResult(): bool
    {
        return $this->result;
    }

    function getErrorMsg(): string
    {
        return $this->errorMsg;
    }

    public function sendEmail(array $data): void
    {
        $this->data = $data;

        try {
            $mail = new PHPMailer(true);
            
            // Modo silencioso ativado (0)
            $mail->SMTPDebug = 0; 

            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();                                            
            
            // ========================================================================
            // DOCAN ENGINE: CONFIGURAÇÃO DIRETA DO GMAIL (PLANO B)
            // ========================================================================
            $mail->Host       = 'smtp.gmail.com';       // Servidor do Google
            $mail->SMTPAuth   = true;                                 
            
            // 🔴 ATENÇÃO: COLOQUE AQUI O SEU E-MAIL DO GMAIL QUE GEROU A SENHA DE APP
            $mail->Username   = 'm33canedo@gmail.com'; 
            
            // Senha de App que você gerou (Sem espaços!)
            $mail->Password   = 'zdmabhotibhbafrj';       
            
            $mail->SMTPSecure = 'ssl'; // Encriptação SSL
            $mail->Port       = 465;   // Porta padrão do SSL do Google

            // Quem está enviando (Pode ser o mesmo e-mail do Username)
            $mail->setFrom('m33canedo@gmail.com', 'Sistema TMNet'); 
            // ========================================================================

            $mail->addAddress($this->data['toEmail'], $this->data['toName']); 

            $mail->isHTML(true);                                  
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHtml'];
            $mail->AltBody = $this->data['contentText'];

            $mail->send();
            $this->result = true;
            
        } catch (\Throwable $e) {
            $this->result = false;
            // Guarda o erro real caso o Gmail recuse a conexão por algum motivo
            $this->errorMsg = $mail->ErrorInfo;
        }
    }
}