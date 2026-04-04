<?php
namespace App\adms\Models\helper;

class AdmsSendWhatsapp {
    public function send(string $number, string $message): void {
        // Remove caracteres não numéricos do telefone
        $number = preg_replace('/\D/', '', $number);
        
        // Exemplo de integração via cURL (Adapte para sua API)
        $data = [
            "number" => "55" . $number, // Garante código do país
            "message" => $message
        ];

        /* $ch = curl_init('SUA_URL_DA_API');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);
        */
    }
}