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
class AdmsEditEmpPrincipal
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
    public function viewEmpPrincipal(int $id): void
    {
        $this->id = $id;

        $viewEmpresas = new \App\adms\Models\helper\AdmsRead();
        $viewEmpresas->fullRead("SELECT emp.id, emp.razao_social, emp.nome_fantasia, emp.cnpj, emp.cep, emp.logradouro, emp.bairro, 
                            emp.cidade, emp.uf, emp.contato, emp.telefone, emp.email, sit.name as situacao, emp.logo
                            FROM adms_emp_principal as emp
                            INNER JOIN adms_sits_empr_unid AS sit ON sit.id=emp.situacao 
                            WHERE emp.id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewEmpresas->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página não encontradas!</p>";
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

        $upEmpresas = new \App\adms\Models\helper\AdmsUpdate();
        $upEmpresas->exeUpdate("adms_emp_principal", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upEmpresas->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Dados da empresa editados com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Dados da empresa não editados com sucesso!</p>";
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
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid as sit ORDER BY name ASC");
        $registry['sit_empresas'] = $list->getResult();

        $list->fullRead("SELECT id id_cont, num_cont FROM adms_contr");
        $registry['num_contr'] = $list->getResult();

        $this->listRegistryAdd = ['sit_empresas' => $registry['sit_empresas'], 'num_contr' => $registry['num_contr']];
        return $this->listRegistryAdd;
    }
}
