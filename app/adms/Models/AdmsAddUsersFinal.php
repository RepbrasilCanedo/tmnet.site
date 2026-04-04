<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddUsersFinal
{
    private array|null $data;
    private bool $result = false;
    private array $listRegistryAdd;

    function getResult(): bool { return $this->result; }

    public function create(array $data): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);

        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email']);

        $valPassword = new \App\adms\Models\helper\AdmsValPassword();
        $valPassword->validatePassword($this->data['password']);

        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user']);

        if ($valEmail->getResult() && $valEmailSingle->getResult() && $valPassword->getResult() && $valUserSingle->getResult()) {
            $this->add();
        } else {
            $this->result = false;
        }
    }

    private function add(): void
    {
        date_default_timezone_set('America/Bahia');

        // Garante que o cadastro pertença à empresa do gestor atual e nível Usuário Final
        $this->data['empresa_id'] = $_SESSION['emp_user'];
        $this->data['adms_access_level_id'] = 14; 
        
        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->data['conf_email'] = password_hash($this->data['password'] . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
        $this->data['created'] = date("Y-m-d H:i:s");

        $createUser = new \App\adms\Models\helper\AdmsCreate();
        $createUser->exeCreate("adms_users_final", $this->data);

        if ($createUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Usuário Final cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao cadastrar usuário final.</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $userLevel = (int) $_SESSION['adms_access_level_id'];
        $empUser = $_SESSION['emp_user'];

        // 1. Queries Globais (Independente do nível de acesso)
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_users ORDER BY name ASC");
        $registry['sit'] = $list->getResult();

        $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels WHERE order_levels >= :order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
        $registry['lev'] = $list->getResult();

        // 2. Query de Empresas/Clientes com base no nível de acesso
        if ($userLevel > 2) {
            $conditions = "WHERE empresa = :empresa";
            $params = "empresa={$empUser}";

            // Filtro específico para Suporte Técnico (12)
            if ($userLevel == 12) {
                //verifica se o ususrio esta vinculado a algum cliente
                $list->fullRead("SELECT cliente_id FROM adms_user_clie WHERE user_id = :user_id", "user_id={$_SESSION['user_id']}");
                $clieUser=$list->getResult();
                if($clieUser){
                    $conditions .= " AND id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = :user_id)";
                    $params .= "&user_id={$_SESSION['user_id']}";
                }else{
                    $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes $conditions", $params);
                }
            }

            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes $conditions", $params);
        } else {
            // Super Admin vê tudo
            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes ORDER BY nome_fantasia ASC");
        }
        
        $registry['emp'] = $list->getResult();

        return $this->listRegistryAdd = [
            'sit' => $registry['sit'], 
            'lev' => $registry['lev'], 
            'emp' => $registry['emp']
        ];
    }
}