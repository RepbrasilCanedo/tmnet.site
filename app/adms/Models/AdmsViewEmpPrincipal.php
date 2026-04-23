<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsViewEmpPrincipal
{
    private bool $result = false;
    private array|null $resultBd;
    private int|string|null $id;

    function getResult(): bool
    {
        return $this->result;
    }

    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    public function viewEmpPrincipal(int $id): void
    {
        $this->id = $id;
        
        // 1. DADOS DO CLUBE
        $viewEmpPrincipal = new \App\adms\Models\helper\AdmsRead();
        $viewEmpPrincipal->fullRead("SELECT emp.id, emp.razao_social, emp.nome_fantasia, emp.cnpj, emp.cep, emp.logradouro, emp.bairro, emp.cidade, 
                emp.uf, emp.contato, emp.telefone, emp.email, sit.name AS name_sit, emp.logo as logo_emp, emp.created, emp.modified
                FROM adms_emp_principal AS emp
                INNER JOIN adms_sits_empr_unid AS sit ON sit.id=emp.situacao
                WHERE emp.id=:id_emp 
                LIMIT :limit", "id_emp={$this->id}&limit=1");

        if ($viewEmpPrincipal->getResult()) {
            $this->resultBd = $viewEmpPrincipal->getResult();

            // =========================================================
            // DOCAN FIX: BUSCAR TORNEIOS E CONTAGEM DE INSCRITOS
            // =========================================================
            $readComp = new \App\adms\Models\helper\AdmsRead();
            $readComp->fullRead(
                "SELECT c.id, c.nome_torneio, c.data_evento, c.status_inscricao,
                        (SELECT COUNT(DISTINCT adms_user_id) FROM adms_inscricoes WHERE adms_competicao_id = c.id AND status_pagamento_id IN (2,3)) as total_inscritos
                 FROM adms_competicoes c
                 WHERE c.empresa_id = :empresa_id
                 ORDER BY c.data_evento DESC",
                "empresa_id={$this->id}"
            );
            
            $competicoes = $readComp->getResult() ?: [];
            $this->resultBd['comp_ativas'] = [];
            $this->resultBd['comp_historico'] = [];
            $hoje = date('Y-m-d');
            
            // Separa os torneios em Ativos e Histórico
            foreach($competicoes as $c) {
                if($c['data_evento'] >= $hoje || $c['status_inscricao'] == 1) {
                    $this->resultBd['comp_ativas'][] = $c;
                } else {
                    $this->resultBd['comp_historico'][] = $c;
                }
            }

            // =========================================================
            // DOCAN FIX: BUSCAR ATLETAS FILIADOS (Para o Relatório)
            // =========================================================
            $readAtl = new \App\adms\Models\helper\AdmsRead();
            $readAtl->fullRead(
                "SELECT u.id, u.name, u.apelido, u.telefone, u.email, u.pontuacao_ranking, u.cidade, u.estado
                 FROM adms_users u
                 INNER JOIN adms_atleta_clube ac ON ac.adms_user_id = u.id
                 WHERE ac.empresa_id = :empresa_id AND u.adms_access_level_id >= 14
                 ORDER BY u.name ASC",
                "empresa_id={$this->id}"
            );
            $this->resultBd['atletas_filiados'] = $readAtl->getResult() ?: [];

            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Dados da empresa não encontrado!</p>";
            $this->result = false;
        }
    }
}