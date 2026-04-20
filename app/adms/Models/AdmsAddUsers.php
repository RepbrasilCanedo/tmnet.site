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
        $this->data['created'] = date("Y-m-d H:i:s");

        // ========================================================================
        // DOCAN FIX: LÓGICA DE FILIAÇÃO (N:N) - ATLETAS vs GESTORES
        // ========================================================================
        $clubeId = 1; // Default TMNet
        if ($_SESSION['adms_access_level_id'] > 2) {
            $clubeId = $_SESSION['emp_user']; // Clube adicionando
        } elseif (!empty($this->data['empresa_id'])) {
            $clubeId = $this->data['empresa_id']; // S-Admin adicionando (escolheu no select)
        }

        // Se for Atleta (14), ele pertence à TMNet (1). Se for gestor, pertence ao Clube ($clubeId)
        if (isset($this->data['adms_access_level_id']) && $this->data['adms_access_level_id'] == 14) {
            $this->data['empresa_id'] = 1;
        } else {
            $this->data['empresa_id'] = $clubeId;
        }

        $createUser = new \App\adms\Models\helper\AdmsCreate();
        $createUser->exeCreate("adms_users", $this->data);

        if ($createUser->getResult()) {
            // Pega o ID do novo usuário gerado pelo banco
            $novoUserId = $createUser->getResult();

            // Se for um atleta e estivermos vinculando a um clube real (não a TMNet pura)
            if (isset($this->data['adms_access_level_id']) && $this->data['adms_access_level_id'] == 14 && $clubeId != 1) {
                $dadosClubeAtleta = [
                    'adms_user_id' => $novoUserId,
                    'empresa_id' => $clubeId,
                    'created' => date("Y-m-d H:i:s")
                ];
                $createRel = new \App\adms\Models\helper\AdmsCreate();
                $createRel->exeCreate("adms_atleta_clube", $dadosClubeAtleta);
            }

            $_SESSION['msg'] = "<p class='alert-success'>Colaborador/Atleta cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não cadastrado!</p>";
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