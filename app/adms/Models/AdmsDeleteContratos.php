<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar Contrato no banco de dados com validação de vínculo
 */
class AdmsDeleteContratos
{
    private bool $result = false;
    private array|null $resultBd;

    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Verifica se o contrato existe, pertence à empresa e se NÃO possui cliente vinculado
     */
    public function deleteContrato(int $id): void
    {
        $viewContrato = new \App\adms\Models\helper\AdmsRead();
        // Buscamos o ID do contrato e o ID do cliente vinculado
        $viewContrato->fullRead(
            "SELECT id, cliente_id FROM adms_contrato WHERE id=:id AND empresa_id=:empresa_id LIMIT :limit",
            "id={$id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewContrato->getResult();

        if ($this->resultBd) {
            // REGRA DE NEGÓCIO: Se o cliente_id não estiver vazio/zero, barra a exclusão
            if (!empty($this->resultBd[0]['cliente_id'])) {
                $_SESSION['msg'] = "<p class='alert-warning'>Ação Bloqueada: Este contrato não pode ser apagado pois está vinculado a um cliente!</p>";
                $this->result = false;
            } else {
                // Se o cliente_id estiver vazio, libera para deletar
                $this->exeDelete($id);
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado ou você não tem permissão para excluí-lo!</p>";
            $this->result = false;
        }
    }

    /**
     * Executa a exclusão no banco de dados
     */
    private function exeDelete(int $id): void
    {
        $deleteContrato = new \App\adms\Models\helper\AdmsDelete();
        $deleteContrato->exeDelete(
            "adms_contrato", 
            "WHERE id=:id AND empresa_id=:empresa_id", 
            "id={$id}&empresa_id={$_SESSION['emp_user']}"
        );

        if ($deleteContrato->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Contrato apagado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível apagar o contrato!</p>";
            $this->result = false;
        }
    }
}