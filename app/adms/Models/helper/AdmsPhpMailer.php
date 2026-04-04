<?php

namespace App\adms\Models\helper;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die();
}

/**
 * Helper para envio de e-mail utilizando PHPMailer
 */
class AdmsPhpMailer
{
    /** @var bool $result Recebe true quando enviar com sucesso */
    private bool $result = false;

    /** @var array $data Recebe as informações do e-mail */
    private array $data;

    function getResult(): bool { return $this->result; }

    /**
     * Envia o e-mail
     * @param array $data [toEmail, toName, subject, contentHtml, contentText]
     */
    public function sendEmail(array $data): void
    {
        $this->data = $data;
        $mail = new PHPMailer(true);

        try {
            // Configurações do Servidor SMTP
            $mail->isSMTP();
            $mail->Host       = EMAIL_HOST;  // Usa a constante
            $mail->SMTPAuth   = true;
            $mail->Username   = EMAIL_USER;  // Usa a constante
            $mail->Password   = EMAIL_PASS;  // Usa a constante
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = EMAIL_PORT;  // Usa a constante
            $mail->CharSet    = 'UTF-8';

            // Remetente e Destinatário
            $mail->setFrom(EMAIL_USER, EMAIL_FROM_NAME);
            $mail->addAddress($this->data['toEmail'], $this->data['toName']);

            // Conteúdo
            $mail->isHTML(true);
            $mail->Subject = $this->data['subject'];
            $mail->Body    = $this->data['contentHtml'];
            $mail->AltBody = $this->data['contentText'];

            $mail->send();
            $this->result = true;
        } catch (Exception $e) {
            $this->result = false;
            // Opcional: Logar o erro $mail->ErrorInfo;
        }
    }
}