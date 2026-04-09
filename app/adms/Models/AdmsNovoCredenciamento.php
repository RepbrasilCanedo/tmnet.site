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

    public function listarClubes(): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        // DOCAN FIX: Removido o filtro "status_id = 1" que estava a esconder as suas empresas!
        $read->fullRead("SELECT id, nome_fantasia FROM adms_emp_principal  WHERE id > 1 ORDER BY nome_fantasia ASC");
        return $read->getResult();
    }

    public function criarCadastro(array $data): void
    {
        $this->data = $data;

        // 1. VALIDAÇÃO DO GOOGLE reCAPTCHA (Anti-Robô)
        if (empty($this->data['g-recaptcha-response'])) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Por favor, confirme que você não é um robô marcando a caixa do reCAPTCHA!</div>";
            $this->result = false;
            return;
        }

        // ==============================================================================
        // 🔴 ATENÇÃO DOCAN: COLOQUE A SUA CHAVE SECRETA DO GOOGLE ABAIXO!
        // ==============================================================================
        $secretKey = "6LckDq0sAAAAAG7vfCcNmoK4qcFroB2lDbdRiZb9"; 
        
        // DOCAN FIX: Usando cURL para contornar o bloqueio de SSL do WampServer
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $secretKey, 
            'response' => $this->data['g-recaptcha-response']
        ]));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Fundamental para testes locais!
        $verifyResponse = curl_exec($curl);
        curl_close($curl);

        $responseData = json_decode($verifyResponse);

        if (!$responseData || !$responseData->success) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Falha na verificação de segurança (Anti-Spam). Tente novamente.</div>";
            $this->result = false;
            return;
        }

        // Limpa os campos que não vão para a base de dados
        unset($this->data['SendNovaConta'], $this->data['g-recaptcha-response']);

        // 2. LIMPEZA DOS DADOS E PADRONIZAÇÃO
        $this->data['user'] = trim(strtolower($this->data['user']));
        $this->data['email'] = trim(strtolower($this->data['email']));
        
        // 3. VERIFICA SE O UTILIZADOR OU E-MAIL JÁ EXISTEM NO SISTEMA GERAL
        $readUser = new \App\adms\Models\helper\AdmsRead();
        $readUser->fullRead("SELECT id FROM adms_users WHERE user = :user OR email = :email LIMIT 1", "user={$this->data['user']}&email={$this->data['email']}");
        
        if ($readUser->getResult()) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Este Nome de Usuário (Login) ou E-mail já está cadastrado no sistema! Escolha outro. (Ex: se já joga num clube, use seunome_clube2).</div>";
            $this->result = false;
            return;
        }

        // 4. CRIPTOGRAFA A SENHA
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);

        // 5. INJETA AS REGRAS DE NEGÓCIO DA FEDERAÇÃO
        $this->data['adms_access_level_id'] = 14; // Nível Atleta
        $this->data['adms_sits_user_id'] = 3;     // Status 3: Aguardando Confirmação
        $this->data['created'] = date("Y-m-d H:i:s");

        // 6. GRAVA NO BANCO DE DADOS (Como os names do HTML batem certo com o Banco, a classe AdmsCreate faz a magia automaticamente)
        $create = new \App\adms\Models\helper\AdmsCreate();
        $create->exeCreate("adms_users", $this->data);

        if ($create->getResult()) {
            $_SESSION['msg'] = "<div class='alert-success'>✅ Credenciamento Solicitado! O seu pedido foi enviado para o clube e está em análise. Aguarde a aprovação do Administrador.</div>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Falha ao solicitar credenciamento. Tente novamente mais tarde.</div>";
            $this->result = false;
        }
    }
}