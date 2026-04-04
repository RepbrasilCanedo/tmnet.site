<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar o usuário no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditUsers
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $listRegistryAdd;

    /** @var array|null $dataExitVal Recebe os campos que devem ser retirados da validação */
    private array|null $dataExitVal;

    /** @return bool Retorna true quando executar o processo com sucesso e false quando houver erro */
    function getResult(): bool
    {
        return $this->result;
    }

    /** @return bool Retorna os detalhes do registro */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo recebe como parametro o ID que será usado para verificar se tem o registro cadastrado no banco de dados
     * @param integer $id
     * @return void
     */
    public function viewUser(int $id): void
    {
        $this->id = $id;

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT usr.id, usr.name, usr.apelido, usr.data_nascimento, usr.mao_dominante, usr.estilo_jogo, usr.sexo, usr.email, usr.tel_1, usr.tel_2, usr.empresa_id,  usr.user, 
                            usr.adms_sits_user_id, usr.adms_access_level_id FROM adms_users AS usr
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                            WHERE usr.id=:id AND lev.order_levels >:order_levels
                            LIMIT :limit",
                            "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
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
     * Instanciar o helper "validatePassword" para validar a senha
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
        $valEmailSingle->validateEmailSingle($this->data['email'], true, $this->data['id']);

        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user'], true, $this->data['id']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valUserSingle->getResult())) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo envia as informações editadas para o banco de dados
     * @return void
     */
    private function edit(): void
    {
        
        date_default_timezone_set('America/Bahia');

        $this->data['modified'] = date("Y-m-d H:i:s");
        $this->data['empresa_id']= $_SESSION['emp_user'];

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Usuário editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        if ($_SESSION['adms_access_level_id'] > 2){

            if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] == 12)) {

                $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sits_users");
                $registry['sit_user'] = $list->getResult();

                $list->fullRead("SELECT clie.id, clie.razao_social, clie.nome_fantasia, clie.cnpjcpf, clie.empresa  FROM adms_clientes as clie
                WHERE clie.empresa = :empresa order by clie.razao_social", "empresa={$_SESSION['emp_user']}");
                $registry['clie_user'] = $list->getResult();
            
                $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes as emp WHERE empresa= :empresa", "empresa={$_SESSION['emp_user']}");
                $registry['emp'] = $list->getResult();

                $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels  WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
                $registry['lev'] = $list->getResult();
            }
        } else {

            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_emp_principal as emp ORDER BY nome_fantasia ASC");
            $registry['emp'] = $list->getResult();

            $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sits_users");
            $registry['sit_user'] = $list->getResult();

            $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels  WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
            $registry['lev'] = $list->getResult();
        }





        $this->listRegistryAdd = ['emp' => $registry['emp'], 'sit_user' => $registry['sit_user'], 'lev' => $registry['lev'], 'clie_user' => $registry['clie_user']];

        return $this->listRegistryAdd;
    }   
}
