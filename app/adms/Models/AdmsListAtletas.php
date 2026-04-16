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
            // S-ADMIN E ADMIN DA PLATAFORMA: Vêem todos os atletas registados na empresa 1 para ativação
            $query = "SELECT usr.id, usr.name, usr.apelido, usr.estilo_jogo, usr.mao_dominante, 
                             usr.pontuacao_ranking, usr.data_nascimento, emp.nome_fantasia, emp.razao_social
                      FROM adms_users AS usr
                      INNER JOIN adms_emp_principal AS emp ON emp.id = usr.empresa_id
                      WHERE usr.adms_access_level_id = 14 
                      ORDER BY usr.pontuacao_ranking DESC";
            $listAtletas->fullRead($query);
        } else {
            // ADMIN DE CLUBE: Vê apenas quem está filiado AO SEU CLUBE 
            // OU quem se inscreveu num torneio DO SEU CLUBE e teve o pagamento aprovado (status 2)
            $query = "SELECT DISTINCT usr.id, usr.name, usr.apelido, usr.estilo_jogo, usr.mao_dominante, 
                             usr.pontuacao_ranking, usr.data_nascimento, emp.nome_fantasia, emp.razao_social
                      FROM adms_users AS usr
                      INNER JOIN adms_emp_principal AS emp ON emp.id = :empresa_id_clube
                      LEFT JOIN adms_inscricoes AS ins ON ins.adms_user_id = usr.id
                      LEFT JOIN adms_competicoes AS comp ON comp.id = ins.adms_competicao_id
                      WHERE usr.adms_access_level_id = 14 
                      AND (
                          usr.clube_filiacao_id = :empresa_id_clube 
                          OR (comp.empresa_id = :empresa_id_clube AND ins.status_pagamento_id = 2)
                      )
                      ORDER BY usr.pontuacao_ranking DESC";
            $listAtletas->fullRead($query, "empresa_id_clube={$empresaLogada}");
        }

        $this->result = $listAtletas->getResult();
    }
}