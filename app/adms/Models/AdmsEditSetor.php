<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar página no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditSetor
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    

    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array $listRegistryAdd;

    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array $listRegistryEdit;

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
     * Metodo para visualizar os detalhes do setor da empresa
     * Recebe o ID do setor que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro.
     * @param integer $id
     * @return void
     */
    public function viewSetor(int $id): void
    {
        $this->id = $id;

        $viewSetor = new \App\adms\Models\helper\AdmsRead();
        $viewSetor->fullRead("SELECT id, name, cont_id, empresa_id, modified FROM adms_setor WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");
        
        $this->resultBd = $viewSetor->getResult();        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor da empresa não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo recebe as informações da empresa que serão validadas
     * Instancia o helper AdmsValEmptyField para validar os campos do formulário     
     * Retira os campos opcionais "icon" e "obs" da validação
     * Chama o metodo edit para fazer a alteração das informações
     * @param array|null $data
     * @return void
     */
    public function update(array $data): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
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

        $upSetor = new \App\adms\Models\helper\AdmsUpdate();
        $upSetor->exeUpdate("adms_setor", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upSetor->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Setor editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor não editado com sucesso!</p>";
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

        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {

            if ($_SESSION['adms_access_level_id'] == 10) {

                $list->fullRead(
                    "SELECT id id_emp, nome_fantasia nome_fantasia_emp, contrato FROM adms_empresa WHERE id= :id ORDER BY nome_fantasia ASC",
                    "id={$_SESSION['emp_user']}"
                );
                $registry['emp_setor'] = $list->getResult();
            } else if ($_SESSION['adms_access_level_id'] == 4) {

                $list->fullRead(
                    "SELECT id id_emp, nome_fantasia nome_fantasia_emp, contrato FROM adms_empresa WHERE contrato = :contrato  ORDER BY nome_fantasia ASC",
                    "contrato={$_SESSION['set_Contr']}"
                );
                $registry['emp_setor'] = $list->getResult();
            }
            $this->listRegistryAdd = ['emp_setor' => $registry['emp_setor']];
        } else {
            $list->fullRead("SELECT id id_cont, num_cont FROM  adms_contr AS cont ORDER BY num_cont ASC");
            $registry['emp_cont'] = $list->getResult();

            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp, contrato FROM adms_empresa ORDER BY nome_fantasia_emp ASC");
            $registry['emp_setor'] = $list->getResult();

            $this->listRegistryAdd = ['emp_cont' => $registry['emp_cont'], 'emp_setor' => $registry['emp_setor']];
        }
        

        return $this->listRegistryAdd;
    }
}
