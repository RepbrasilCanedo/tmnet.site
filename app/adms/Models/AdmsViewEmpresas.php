<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar detalhes da Empresa/Cliente e seus Contratos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewEmpresas
{
    private bool $result = false;
    private array|null $resultBd;
    private array|null $resultContratos = null; // Nova variável para guardar os contratos
    private int|string|null $id;

    public function getResult(): bool
    {
        return $this->result;
    }

    public function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function getResultContratos(): array|null
    {
        return $this->resultContratos;
    }

    /**
     * Busca os dados do cliente (Adicionada validação por empresa_id para segurança)
     */
    public function viewEmpresas(int $id): void
    {
        $this->id = $id;

        $viewEmpresas = new \App\adms\Models\helper\AdmsRead();
        $viewEmpresas->fullRead(
            "SELECT emp.id, emp.razao_social, emp.nome_fantasia, emp.cnpjcpf, emp.cep, emp.logradouro, emp.bairro, emp.cidade, emp.uf, sit.name AS name_sit, emp.created, emp.modified
             FROM adms_clientes AS emp
             INNER JOIN adms_sits_empr_unid AS sit ON sit.id=emp.situacao 
             WHERE emp.id=:id AND emp.empresa=:empresa_id LIMIT :limit", 
            "id={$this->id}&empresa_id={$_SESSION['emp_user']}&limit=1"
        );

        $this->resultBd = $viewEmpresas->getResult();
        
        if ($this->resultBd) {
            $this->result = true;
            // Se achou o cliente, busca os contratos vinculados a ele
            $this->viewContratosCliente($this->id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cliente não encontrado ou não pertence a esta empresa!</p>";
            $this->result = false;
        }
    }

    /**
     * NOVO: Busca todos os contratos vinculados a este cliente
     */
    private function viewContratosCliente(int $cliente_id): void
    {
        $viewContratos = new \App\adms\Models\helper\AdmsRead();
        $viewContratos->fullRead(
            "SELECT c.id, c.name, c.inicio_contr, c.final_contr, c.tipo, c.quant, tc.name AS tipo_nome, sit.name AS sit_nome 
             FROM adms_contrato AS c
             LEFT JOIN adms_tipo_contrato AS tc ON tc.id = c.tipo_contr 
             LEFT JOIN adms_contr_sit AS sit ON sit.id = c.status 
             WHERE c.cliente_id = :cliente_id AND c.empresa_id = :empresa_id
             ORDER BY c.id DESC",
            "cliente_id={$cliente_id}&empresa_id={$_SESSION['emp_user']}"
        );

        $this->resultContratos = $viewContratos->getResult();
    }
}