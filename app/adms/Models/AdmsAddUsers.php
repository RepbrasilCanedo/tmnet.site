<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddUsers
{
    private array|null $data;
    private bool $result;
    private array $listRegistryAdd;

    function getResult(): bool
    {
        return $this->result;
    }

    public function create(array $data)
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

        $valUserSingleLogin = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingleLogin->validateUserSingle($this->data['user']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valPassword->getResult()) and ($valUserSingleLogin->getResult())) {
            $this->add();
        } else {
            $this->result = false;
        }
    }

    private function add(): void
    {
        date_default_timezone_set('America/Bahia');

        $this->data['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->data['conf_email'] = password_hash($this->data['password'] . date("Y-m-d H:i:s"), PASSWORD_DEFAULT);
        if ($_SESSION['adms_access_level_id'] > 2) {
            $this->data['empresa_id'] = $_SESSION['emp_user'];
        }        
        $this->data['created'] = date("Y-m-d H:i:s");

        $createUser = new \App\adms\Models\helper\AdmsCreate();
        $createUser->exeCreate("adms_users", $this->data);

        if ($createUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Usuário cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        if ($_SESSION['adms_access_level_id'] > 2){
            $list->fullRead("SELECT sit.id id_sit, sit.name name_sit FROM adms_sits_users as sit");
            $registry['sit'] = $list->getResult();

            $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels  WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
            $registry['lev'] = $list->getResult();

            $this->listRegistryAdd = ['sit' => $registry['sit'], 'lev' => $registry['lev']];
            return $this->listRegistryAdd;
        } else {
            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_emp_principal ORDER BY nome_fantasia ASC");
            $registry['emp'] = $list->getResult();

            $list->fullRead("SELECT sit.id id_sit, sit.name name_sit FROM adms_sits_users as sit");
            $registry['sit'] = $list->getResult();

            $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels  WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
            $registry['lev'] = $list->getResult();

            $this->listRegistryAdd = ['emp' => $registry['emp'], 'sit' => $registry['sit'], 'lev' => $registry['lev']];
            return $this->listRegistryAdd;
        }
    }
}