<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListAtletas
{
    private array|null $result;

    function getResult(): array|null
    {
        return $this->result;
    }

    public function listAtletas(): void
    {
        $listAtletas = new \App\adms\Models\helper\AdmsRead();
        
        $nivelLogado = (int)$_SESSION['adms_access_level_id'];
        $empresaLogada = (int)$_SESSION['emp_user'];

        // ========================================================================
        // DOCAN LOGIC: FILTRO DE VISIBILIDADE GLOBAL VS CLUBE
        // ========================================================================
        
        if ($nivelLogado <= 2) {
            // S-ADMIN E ADMIN DA PLATAFORMA: Vêem todos os atletas registados
            $query = "SELECT usr.id, usr.name, usr.apelido, usr.estilo_jogo, usr.mao_dominante, 
                             usr.pontuacao_ranking, usr.data_nascimento, emp.nome_fantasia, emp.razao_social
                      FROM adms_users AS usr
                      LEFT JOIN adms_emp_principal AS emp ON emp.id = usr.empresa_id
                      WHERE usr.adms_access_level_id = 14 
                      ORDER BY usr.pontuacao_ranking DESC";
            $listAtletas->fullRead($query);
        } else {
            // ========================================================================
            // DOCAN FIX: ADMIN DE CLUBE: Vê apenas quem está na tabela 'adms_atleta_clube'
            // ========================================================================
            $query = "SELECT usr.id, usr.name, usr.apelido, usr.estilo_jogo, usr.mao_dominante, 
                             usr.pontuacao_ranking, usr.data_nascimento, emp.nome_fantasia, emp.razao_social
                      FROM adms_users AS usr
                      INNER JOIN adms_atleta_clube AS ac ON ac.adms_user_id = usr.id
                      INNER JOIN adms_emp_principal AS emp ON emp.id = ac.empresa_id
                      WHERE usr.adms_access_level_id = 14 
                      AND ac.empresa_id = :empresa_id_clube
                      ORDER BY usr.pontuacao_ranking DESC";
            $listAtletas->fullRead($query, "empresa_id_clube={$empresaLogada}");
        }

        $this->result = $listAtletas->getResult();
    }
}