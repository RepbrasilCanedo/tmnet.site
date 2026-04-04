<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar o perfil do usuario
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditProfile
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $data;

    /** @var array|null $dataExitVal Recebe os campos que devem ser retirados da validação */
    private array|null $dataExitVal;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo faz a pesquisa das informações do usuário que serão editadas na View
     * @return void
     */
    public function viewProfile(): void
    {
       
            $viewUser = new \App\adms\Models\helper\AdmsRead();
            $viewUser->fullRead("SELECT id, name, apelido, email, tel_1, tel_2, user FROM adms_users
                            WHERE id=:id LIMIT :limit","id=" . $_SESSION['user_id'] . "&limit=1");

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo recebe as informações do usuário que serão validadas
     * Instancia o helper AdmsValEmptyField para validar os campos do formulário
     * Retira o campo opcional "apelido" da validação
     * Chama o metodo valInput para validar os campos especificos do formulário     
     * @param array|null $data
     * @return void
     */
    public function update(array $data): void
    {
        $this->data = $data;

        $this->dataExitVal['apelido'] = $this->data['apelido'];
        unset($this->data['apelido']);

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    /** 
     * Instanciar o helper "AdmsValEmail" para verificar se o e-mail válido
     * Instanciar o helper "AdmsValEmailSingle" para verificar se o e-mail não está cadastrado no banco de dados, não permitido cadastro com e-mail duplicado
     * Instanciar o helper "validateUserSingle" para verificar se o usuário não está cadastrado no banco de dados, não permitido cadastro com usuário duplicado
     * Instanciar o método "edit" quando não houver nenhum erro de preenchimento 
     * Retorna FALSE quando houve algum erro
     * 
     * @return void
     */
    private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email'], true, $_SESSION['user_id']);

        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user'], true, $_SESSION['user_id']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valUserSingle->getResult())) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo atualiza as informações no usuário no banco de dados
     * Salva os campos name, apelido e email na sessão
     * @return void
     */
    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");
        $this->data['apelido'] = $this->dataExitVal['apelido'];
        
        if ($_SESSION['adms_access_level_id'] == 14) {
            $upUser = new \App\adms\Models\helper\AdmsUpdate();
            $upUser->exeUpdate("adms_users_final", $this->data, "WHERE id=:id", "id=" . $_SESSION['user_id']);
        }else{
            $upUser = new \App\adms\Models\helper\AdmsUpdate();
            $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id=" . $_SESSION['user_id']);
        }

        if ($upUser->getResult()) {
            $_SESSION['user_name'] = $this->data['name'];
            $_SESSION['user_nickname'] = $this->data['apelido'];
            $_SESSION['user_email'] = $this->data['email'];
            $_SESSION['msg'] = "<p class='alert-success'>Perfil editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não editado com sucesso!</p>";
            $this->result = false;
        }
    }
}
