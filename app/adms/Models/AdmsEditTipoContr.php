<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar Tipo de Contrato no banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditTipoContr
{
    private array|null $data;
    private array|null $resultBd;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Busca os dados do tipo de contrato no banco para preencher o formulário
     */
    public function viewTipoContr(int $id): void
    {
        $viewContr = new \App\adms\Models\helper\AdmsRead();
        $viewContr->fullRead(
            "SELECT id, name FROM adms_tipo_contrato WHERE id=:id AND empresa_id=:empresa_id LIMIT :limit",
            "id={$id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewContr->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de Contrato não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Valida e inicia a atualização do tipo de contrato
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
     * Executa a atualização no banco de dados
     */
    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upContr = new \App\adms\Models\helper\AdmsUpdate();
        $upContr->exeUpdate("adms_tipo_contrato", $this->data, "WHERE id=:id AND empresa_id=:empresa_id", "id={$this->data['id']}&empresa_id={$_SESSION['emp_user']}");

        if ($upContr->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Tipo de Contrato editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível editar o Tipo de Contrato!</p>";
            $this->result = false;
        }
    }
}