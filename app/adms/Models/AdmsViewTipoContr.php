<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar Tipo de Contrato no banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewTipoContr
{
    private bool $result = false;
    private array|null $resultBd;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Busca os detalhes do tipo de contrato no banco de dados
     * Faz um INNER JOIN para buscar o nome do status correspondente
     */
    public function viewTipoContr(int $id): void
    {
        $viewContr = new \App\adms\Models\helper\AdmsRead();
        $viewContr->fullRead(
            "SELECT type.id, type.name, type.created, type.modified, sit.name as name_sit 
             FROM adms_tipo_contrato AS type
             INNER JOIN adms_contr_sit AS sit ON sit.id=type.status 
             WHERE type.id=:id AND type.empresa_id=:empresa_id 
             LIMIT :limit",
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
}