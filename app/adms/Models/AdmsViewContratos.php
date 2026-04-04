<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsViewContratos
{
    private bool $result = false;
    private array|null $resultBd;
    private array|null $resultAnexos = null; // Nova variável para os anexos

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function getResultAnexos(): array|null
    {
        return $this->resultAnexos;
    }

    public function viewContrato(int $id): void
    {
        $viewQuery = new \App\adms\Models\helper\AdmsRead();
        $viewQuery->fullRead(
            "SELECT c.*, 
                    tc.name AS tipo_nome, 
                    sit.name AS sit_nome, 
                    cl.razao_social AS cliente_nome 
             FROM adms_contrato AS c
             LEFT JOIN adms_tipo_contrato AS tc ON tc.id = c.tipo_contr 
             LEFT JOIN adms_contr_sit AS sit ON sit.id = c.status 
             LEFT JOIN adms_clientes AS cl ON cl.id = c.cliente_id 
             WHERE c.id=:id AND c.empresa_id=:empresa_id 
             LIMIT :limit",
            "id={$id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewQuery->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
            // Busca os anexos vinculados a este contrato
            $this->viewAnexosContrato($id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Busca os PDFs/Anexos vinculados ao contrato
     */
    private function viewAnexosContrato(int $cont_id): void
    {
        $viewAnexos = new \App\adms\Models\helper\AdmsRead();
        $viewAnexos->fullRead(
            "SELECT id, cont_id, image, created 
             FROM adms_contr_anexos 
             WHERE cont_id = :cont_id 
             ORDER BY id DESC",
            "cont_id={$cont_id}"
        );
        $this->resultAnexos = $viewAnexos->getResult();
    }
}