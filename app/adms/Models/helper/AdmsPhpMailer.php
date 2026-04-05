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

    function getResult(): bool
    {
        return $this->result;
    }

    public function sendEmail(array $data): void
    {
        $this->data = $data;

        try {
            $mail = new PHPMailer(true);
            
            // Modo silencioso ativado (0) - Sem letras verdes na tela!
            $mail->SMTPDebug = 0; 

            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();                                            
            $mail->Host       = EMAIL_HOST;       
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = EMAIL_USER;       
            $mail->Password   = EMAIL_PASS;       
            $mail->SMTPSecure = 'ssl';           
            $mail->Port       = EMAIL_PORT;       

            $mail->setFrom(EMAIL_USER, EMAIL_FROM_NAME); 
            $mail->addAddress($this->data['toEmail'], $this->data['toName']); 

            $mail->isHTML(true);                                  
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHtml'];
            $mail->AltBody = $this->data['contentText'];

            $mail->send();
            $this->result = true;
            
        } catch (\Throwable $e) {
            // Em vez de quebrar a tela, agora ele apenas diz à Controller que falhou
            $this->result = false;
        }
    }
}