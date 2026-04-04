<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditUsersFinal
{
    private bool $result = false;
    private array|null $resultBd;
    private int|string|null $id;
    private array|null $data;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewUserFinal(int $id): void
    {
        $this->id = $id;
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        
        // Incluído usr_final.adms_sits_user_id para o Select funcionar
        $query = "SELECT usr_final.id, usr_final.name AS name_usr_final, usr_final.email AS email_usr_final, 
                         usr_final.tel_1 AS tel_1_usr_final, usr_final.user AS user_usr_final, 
                         usr_final.adms_sits_user_id, usr_final.empresa_id AS empresa_id_usr_final,
                         usr_final.cliente_id, usr_final.adms_access_level_id
                  FROM adms_users_final as usr_final
                  INNER JOIN adms_access_levels AS lev ON lev.id = usr_final.adms_access_level_id
                  WHERE usr_final.id = :id_usr AND lev.order_levels > :order_levels LIMIT 1";

        $viewUser->fullRead($query, "id_usr={$this->id}&order_levels={$_SESSION['order_levels']}");
        $this->resultBd = $viewUser->getResult();
        $this->result = (bool) $this->resultBd;

        if (!$this->result) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
        }
    }

    public function update(array $data): void
    {
        $this->data = $data;
        
        // 1. Tratamento da Senha Opcional: 
        // Se a senha estiver vazia, removemos para não validar campo vazio
        if (empty($this->data['password'])) {
            unset($this->data['password']);
        }

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    /** * Validação de inputs únicos (E-mail e Usuário)
     */
    private function valInput(): void
    {
        // 1. Valida o formato do e-mail
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        // 2. Valida se o e-mail já existe em OUTRO registro (passando o ID atual para ignorar o próprio registro)
        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email'], true, $this->data['id']);

        // 3. Valida se o usuário já existe em OUTRO registro
        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user'], true, $this->data['id']);

        // 4. Executa a edição apenas se todas as validações passarem
        if ($valEmail->getResult() && $valEmailSingle->getResult() && $valUserSingle->getResult()) {
            $this->edit();
        } else {
            // Se falhar, o erro já terá sido setado na sessão pelo Helper
            $this->result = false;
        }
    }

    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");

        // 2. Só criptografa se uma nova senha foi enviada
        if (isset($this->data['password'])) {
            $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        }

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users_final", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Usuário editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao atualizar banco de dados.</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $userLevel = $_SESSION['adms_access_level_id'];
        
        // Unificando lógica de selects
        $list->fullRead("SELECT id AS id_sit, name AS name_sit FROM adms_sits_users ORDER BY name ASC");
        $registry['sit_user'] = $list->getResult();

        $list->fullRead("SELECT id AS id_lev, name AS name_lev FROM adms_access_levels WHERE order_levels > :ord ORDER BY order_levels ASC", "ord={$_SESSION['order_levels']}");
        $registry['lev'] = $list->getResult();

        // Filtro de empresa baseado no nível
        if ($userLevel > 2 && $userLevel != 7) {
            $list->fullRead("SELECT id AS id_emp, nome_fantasia AS nome_fantasia_emp FROM adms_clientes WHERE empresa = :emp", "emp={$_SESSION['emp_user']}");
        } else {
            $list->fullRead("SELECT id AS id_emp, nome_fantasia AS nome_fantasia_emp FROM adms_clientes ORDER BY nome_fantasia ASC");
        }
        $registry['emp'] = $list->getResult();

        return ['emp' => $registry['emp'], 'sit_user' => $registry['sit_user'], 'lev' => $registry['lev']];
    }
}