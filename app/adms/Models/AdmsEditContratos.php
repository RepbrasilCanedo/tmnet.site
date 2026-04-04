<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar Contrato no banco de dados
 */
class AdmsEditContratos
{
    private array|null $data;
    private array|null $resultBd;
    private bool $result = false;
    private array $listRegistryEdit;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Busca os dados do contrato atual
     */
    public function viewContrato(int $id): void
    {
        $viewContrato = new \App\adms\Models\helper\AdmsRead();
        $viewContrato->fullRead(
            "SELECT * FROM adms_contrato WHERE id=:id AND empresa_id=:empresa_id LIMIT :limit",
            "id={$id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewContrato->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Busca os dados para os selects (Tipos, Status e Clientes)
     */
    public function listSelect(): array
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT id, name FROM adms_tipo_contrato WHERE empresa_id = :empresa_id ORDER BY name ASC", "empresa_id={$_SESSION['emp_user']}");
        $registry['tipo_contr'] = $read->getResult();

        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, name FROM adms_contr_sit ORDER BY name ASC");
        $registry['status'] = $read->getResult();

        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, razao_social, nome_fantasia FROM adms_clientes WHERE empresa = :empresa_id ORDER BY razao_social ASC", "empresa_id={$_SESSION['emp_user']}");
        $registry['clientes'] = $read->getResult();

        $this->listRegistryEdit = $registry;

        return $this->listRegistryEdit;
    }

    /**
     * Valida e atualiza os dados
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

    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upContrato = new \App\adms\Models\helper\AdmsUpdate();
        $upContrato->exeUpdate(
            "adms_contrato", 
            $this->data, 
            "WHERE id=:id AND empresa_id=:empresa_id", 
            "id={$this->data['id']}&empresa_id={$_SESSION['emp_user']}"
        );

        if ($upContrato->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Contrato editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível editar o contrato!</p>";
            $this->result = false;
        }
    }
}