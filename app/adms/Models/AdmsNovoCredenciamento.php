<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsNovoCredenciamento
{
    private array|null $data;
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function criarCadastro(array $data): void
    {
        $this->data = $data;

        // 1. VALIDAÇÃO DO GOOGLE reCAPTCHA (Anti-Robô)
        if (empty($this->data['g-recaptcha-response'])) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Por favor, confirme que você não é um robô marcando a caixa do reCAPTCHA!</div>";
            $this->result = false;
            return;
        }

        // CHAVE SECRETA DE TESTE LOCAL (Trocar na nuvem)
        //$secretKey = "6Ld9RrAsAAAAAKrEK7bPB7JU3fH4N3ogAn9Dt1Di"; captcha local host
        $secretKey = "6LckDq0sAAAAAG7vfCcNmoK4qcFroB2lDbdRiZb9"; 

        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $secretKey, 
            'response' => $this->data['g-recaptcha-response']
        ]));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
        $verifyResponse = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($verifyResponse);

        if (!$responseData || !$responseData->success) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Falha na verificação de segurança (Anti-Spam). Tente novamente.</div>";
            $this->result = false;
            return;
        }

        // Limpa os campos de verificação
        unset($this->data['SendNovaConta'], $this->data['g-recaptcha-response']);

        // 2. LIMPEZA DOS DADOS E PADRONIZAÇÃO
        $this->data['user'] = trim(strtolower($this->data['user']));
        $this->data['email'] = trim(strtolower($this->data['email']));

        // ==============================================================================
        // DOCAN ENGINE: CRUZAMENTO DE GÊNERO COM CATEGORIA (OLÍMPICA/PARALÍMPICA)
        // ==============================================================================
        $gen = $this->data['genero_front'] ?? 'M';
        $tipo = $this->data['tipo_atleta_front'] ?? 'Olimpico';

        if ($gen === 'M' && $tipo === 'Olimpico') $this->data['sexo'] = 1;
        elseif ($gen === 'F' && $tipo === 'Olimpico') $this->data['sexo'] = 2;
        elseif ($gen === 'M' && $tipo === 'Paralimpico') $this->data['sexo'] = 3;
        elseif ($gen === 'F' && $tipo === 'Paralimpico') $this->data['sexo'] = 4;
        else $this->data['sexo'] = 1; // Fallback de segurança

        // Apaga os campos virtuais do Front-End para a gravação no banco não dar erro
        unset($this->data['genero_front'], $this->data['tipo_atleta_front']);
        // ==============================================================================
        
        // 3. VERIFICA SE O UTILIZADOR OU E-MAIL JÁ EXISTEM NO SISTEMA GERAL
        $readUser = new \App\adms\Models\helper\AdmsRead();
        $readUser->fullRead("SELECT id FROM adms_users WHERE user = :user OR email = :email LIMIT 1", "user={$this->data['user']}&email={$this->data['email']}");
        
        if ($readUser->getResult()) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Este Nome de Usuário (Login) ou E-mail já está cadastrado no sistema! Escolha outro.</div>";
            $this->result = false;
            return;
        }

        // 4. CRIPTOGRAFA A SENHA
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);

        // 5. INJETA AS REGRAS DA PLATAFORMA TMNET
        $this->data['empresa_id'] = 1;            // Empresa 1 (Plataforma Global)
        $this->data['adms_access_level_id'] = 14; // Nível Atleta
        $this->data['adms_sits_user_id'] = 3;     // Status 3: Aguardando Confirmação
        $this->data['created'] = date("Y-m-d H:i:s");

        // 6. GRAVA NO BANCO DE DADOS
        $create = new \App\adms\Models\helper\AdmsCreate();
        $create->exeCreate("adms_users", $this->data);

        if ($create->getResult()) {
            $_SESSION['msg'] = "<div class='alert-success'>✅ Conta Criada! O seu pedido de registo na Plataforma TMNet foi enviado com sucesso e está em análise. Você recebera um WhastApp confirmando seu cadastro.</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Falha ao solicitar credenciamento. Tente novamente mais tarde.</div>";
            $this->result = false;
        }
    }
}