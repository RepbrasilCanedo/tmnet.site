<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar Tipo de Contrato no banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteTipoContr
{
    private bool $result = false;
    private array|null $resultBd;

    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Verifica se o registro existe e pertence à empresa antes de apagar
     */
    public function deleteTipoContr(int $id): void
    {
        $viewContr = new \App\adms\Models\helper\AdmsRead();
        $viewContr->fullRead(
            "SELECT id FROM adms_tipo_contrato WHERE id=:id AND empresa_id=:empresa_id LIMIT :limit",
            "id={$id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewContr->getResult();

        if ($this->resultBd) {
            $this->exeDelete($id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de Contrato não encontrado ou você não tem permissão para excluí-lo!</p>";
            $this->result = false;
        }
    }

    /**
     * Executa a exclusão no banco de dados
     */
    private function exeDelete(int $id): void
    {
        $deleteContr = new \App\adms\Models\helper\AdmsDelete();
        $deleteContr->exeDelete(
            "adms_tipo_contrato", 
            "WHERE id=:id AND empresa_id=:empresa_id", 
            "id={$id}&empresa_id={$_SESSION['emp_user']}"
        );

        if ($deleteContr->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Tipo de Contrato apagado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível apagar o Tipo de Contrato!</p>";
            $this->result = false;
        }
    }
}