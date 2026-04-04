<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar Contrato no banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddContratos
{
    private array|null $data;
    private bool $result = false;
    private array $listRegistryAdd;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getListRegistryAdd(): array
    {
        return $this->listRegistryAdd;
    }

    /**
     * Valida e inicia o cadastro do contrato
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
     * Executa o cadastro no banco de dados
     */
    private function add(): void
    {
        $this->data['empresa_id'] = $_SESSION['emp_user'] ?? $_SESSION['user_id'];
        $this->data['created'] = date("Y-m-d H:i:s");

        $createContrato = new \App\adms\Models\helper\AdmsCreate();
        $createContrato->exeCreate("adms_contrato", $this->data);

        if ($createContrato->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Contrato cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível cadastrar o contrato!</p>";
            $this->result = false;
        }
    }

    /**
     * Busca os dados para preencher os campos <select> do formulário
     */
    /**
     * Busca os dados para preencher os campos <select> do formulário
     */
    public function listSelect(): array
    {
        // Busca os Tipos de Contrato da empresa logada
        $readTipoContr = new \App\adms\Models\helper\AdmsRead();
        $readTipoContr->fullRead("SELECT id, name FROM adms_tipo_contrato WHERE empresa_id = :empresa_id ORDER BY name ASC", "empresa_id={$_SESSION['emp_user']}");
        $registry['tipo_contr'] = $readTipoContr->getResult();

        // Busca os Status de Contrato disponíveis
        $readStatus = new \App\adms\Models\helper\AdmsRead();
        $readStatus->fullRead("SELECT id, name FROM adms_contr_sit ORDER BY name ASC");
        $registry['status'] = $readStatus->getResult();

        // NOVO: Busca os Clientes vinculados à empresa logada
        $readClientes = new \App\adms\Models\helper\AdmsRead();
        $readClientes->fullRead("SELECT id, razao_social, nome_fantasia FROM adms_clientes WHERE empresa = :empresa_id ORDER BY razao_social ASC", "empresa_id={$_SESSION['emp_user']}");
        $registry['clientes'] = $readClientes->getResult();

        $this->listRegistryAdd = $registry;
        return $this->listRegistryAdd;
    }
}