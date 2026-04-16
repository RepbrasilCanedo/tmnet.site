<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsNovoClube
{
    private array|null $data;
    private bool $result = false;

    function getResult(): bool
    {
        return $this->result;
    }

    public function create(array $data): void
    {
        $this->data = $data;

        if (isset($this->data['SendNewClub'])) {
            unset($this->data['SendNewClub']);
        }

        // 1. VALIDAÇÃO DO CAPTCHA (Google reCAPTCHA v2)
        // CHAVE SECRETA DE TESTE LOCAL (Trocar na nuvem)
        $secretKey = "6Ld9RrAsAAAAAKrEK7bPB7JU3fH4N3ogAn9Dt1Di";
        //$secretKey = "6LckDq0sAAAAAG7vfCcNmoK4qcFroB2lDbdRiZb9"; --- captcha Nuvem


        $captchaResponse = $this->data['g-recaptcha-response'] ?? '';
        
        if (empty($captchaResponse)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Por favor, marque a caixa 'Não sou um robô'.</p>";
            $this->result = false;
            return;
        }

        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}");
        $responseData = json_decode($verifyResponse);
        
        if (!$responseData->success) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha na validação de segurança (CAPTCHA).</p>";
            $this->result = false;
            return;
        }
        unset($this->data['g-recaptcha-response']); // Remove do array para não dar erro no banco

        // 2. VALIDAÇÃO MATEMÁTICA DE CPF E CNPJ
        $cpfCnpjLimpo = preg_replace('/[^0-9]/', '', $this->data['cpf_cnpj']);
        if (!$this->validaCpfCnpj($cpfCnpjLimpo)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: O CPF ou CNPJ informado é inválido!</p>";
            $this->result = false;
            return;
        }

        // Valida se o E-mail e Usuário já existem no sistema
        $valEmail = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmail->validateEmailSingle($this->data['email']);
        
        $valUser = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUser->validateUserSingle($this->data['user']);

        if ($valEmail->getResult() && $valUser->getResult()) {
            $this->inserirClubeEUsuario();
        } else {
            $this->result = false;
        }
    }

    private function inserirClubeEUsuario(): void
    {
        // DADOS DO CLUBE (EMPRESA)
        $dadosClube = [
            'nome_fantasia' => $this->data['nome_clube'],
            'razao_social' => $this->data['nome_clube'] . ' - Cadastro Web',
            'cnpj' => $this->data['cpf_cnpj'],
            'telefone' => $this->data['telefone'],
            'cep' => $this->data['cep'],
            'logradouro' => $this->data['logradouro'] ?? '', // DOCAN FIX: Recebendo a Rua
            'bairro' => $this->data['bairro'] ?? '',         // DOCAN FIX: Recebendo o Bairro'cidade' => $this->data['cidade'] ?? '',
            'cidade' => $this->data['cidade'] ?? '',
            'estado' => $this->data['estado'] ?? '',            
            'contato' => $this->data['nome_responsavel'] ?? '',
            'email' => $this->data['email'],
            'situacao' => 3, // Status 3 = Aguardando Ativação
            'created' => date("Y-m-d H:i:s")
        ];

        $createClube = new \App\adms\Models\helper\AdmsCreate();
        $createClube->exeCreate("adms_emp_principal", $dadosClube);
        $idClubeCriado = $createClube->getResult();

        if ($idClubeCriado) {
            // DADOS DO USUÁRIO (ADMIN DO CLUBE)
            $dadosUser = [
                'name' => $this->data['nome_responsavel'],
                'email' => $this->data['email'],
                'telefone' => $this->data['telefone'],
                'user' => $this->data['user'],
                'password' => password_hash($this->data['password'], PASSWORD_DEFAULT),
                'adms_access_level_id' => 4, // Nível 3 = Administrador de Clube
                'adms_sits_user_id' => 3, // Status 3 = Aguardando Ativação
                'empresa_id' => $idClubeCriado,
                'created' => date("Y-m-d H:i:s")
            ];

            $createUser = new \App\adms\Models\helper\AdmsCreate();
            $createUser->exeCreate("adms_users", $dadosUser);

            if ($createUser->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>✅ Clube cadastrado com sucesso! O seu acesso está em análise pela plataforma e será liberado em breve.</p>";
                $this->result = true;
            } else {
                $deleteClube = new \App\adms\Models\helper\AdmsDelete();
                $deleteClube->exeDelete("adms_emp_principal", "WHERE id = :id", "id={$idClubeCriado}");
                
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível criar o usuário responsável.</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível registrar o Clube.</p>";
            $this->result = false;
        }
    }

    // DOCAN FIX: Motor de Validação Real de CPF e CNPJ
    private function validaCpfCnpj($valor): bool 
    {
        if (strlen($valor) == 11) {
            // Valida CPF
            if (preg_match('/(\d)\1{10}/', $valor)) return false;
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $valor[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($valor[$c] != $d) return false;
            }
            return true;
        } elseif (strlen($valor) == 14) {
            // Valida CNPJ
            if (preg_match('/(\d)\1{13}/', $valor)) return false;
            $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
            for ($i = 0, $n = 0; $i < 12; $n += $valor[$i] * $b[++$i]);
            if ($valor[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
            for ($i = 0, $n = 0; $i <= 12; $n += $valor[$i] * $b[$i++]);
            if ($valor[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
            return true;
        }
        return false;
    }
}