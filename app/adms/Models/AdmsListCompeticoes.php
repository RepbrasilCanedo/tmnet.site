<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListCompeticoes
{
    private array|null $result;

    function getResult(): array|null
    {
        return $this->result;
    }

    /**
     * @param array|null $search Dados vindos do formulário de busca
     */
    public function list(array|null $search = null): void
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        
        // Verifica se o usuário digitou algo na busca
        if (!empty($search['search_nome'])) {
            $list->fullRead(
                "SELECT id, empresa_id, nome_torneio, data_evento, local_evento, tipo_competicao, categoria_cbtm, fator_multiplicador, observacoes
                 FROM adms_competicoes 
                 WHERE empresa_id = :empresa AND nome_torneio LIKE :nome
                 ORDER BY data_evento DESC", 
                "empresa={$_SESSION['emp_user']}&nome=%{$search['search_nome']}%"
            );
        } else {
            // Busca padrão sem filtro
            $list->fullRead(
                "SELECT id, empresa_id, nome_torneio, data_evento, local_evento, tipo_competicao, categoria_cbtm, fator_multiplicador, observacoes
                 FROM adms_competicoes 
                 WHERE empresa_id = :empresa
                 ORDER BY data_evento DESC", 
                "empresa={$_SESSION['emp_user']}"
            );
        }

        $this->result = $list->getResult();
    }
}