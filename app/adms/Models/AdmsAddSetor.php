<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Model cadastrar setor no banco de Dados
 * @author Daniel Canedo <docan2006@gmail.com>
 */
class AdmsAddSetor
{
    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array $listRegistryAdd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Recebe os valores do formulário.
     * Instancia o helper "AdmsValEmptyField" para verificar se todos os campos estão preenchidos 
     * Verifica se todos os campos estão preenchidos e instancia o método "valInput" para validar os dados dos campos
     * Retorna FALSE quando algum campo está vazio
     * 
     * @param array $data Recebe as informações do formulário
     * 
     * @return void
     */
    public function create(array $data): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->add();
        } else {
            $this->result = false;
        }
    }

    /** 
     * Cadastrar cor no banco de dados
     * Retorna TRUE quando cadastrar a cor com sucesso
     * Retorna FALSE quando não cadastrar a cor
     * 
     * @return void
     */
    private function add(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['created'] = date("Y-m-d H:i:s");

        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {
            $this->data['cont_id'] = $_SESSION['set_Contr'];
        }

        $createSetor = new \App\adms\Models\helper\AdmsCreate();
        $createSetor->exeCreate("adms_setor", $this->data);

        if ($createSetor->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Setor cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor não cadastrado com sucesso!</p>";
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
            } else if ($_SESSION['adms_access_level_id'] == 13) {

                $list->fullRead(
                    "SELECT id id_emp, nome_fantasia nome_fantasia_emp, contrato FROM adms_empresa WHERE contrato = :contrato  ORDER BY nome_fantasia ASC",
                    "contrato={$_SESSION['set_Contr']}"
                );
                $registry['emp_setor'] = $list->getResult();
            $this->listRegistryAdd = ['emp_setor' => $registry['emp_setor']];
            }
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
