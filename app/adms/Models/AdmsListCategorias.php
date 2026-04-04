<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListCategorias
{
    private array|null $result;

    function getResult(): array|null { return $this->result; }

    public function listCategorias(): void
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead(
            "SELECT id, nome, idade_minima, idade_maxima, pontuacao_minima, pontuacao_maxima 
             FROM adms_categorias 
             WHERE empresa_id = :empresa 
             ORDER BY nome ASC", 
            "empresa={$_SESSION['emp_user']}"
        );
        $this->result = $list->getResult();
    }
}