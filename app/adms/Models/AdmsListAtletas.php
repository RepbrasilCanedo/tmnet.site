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
        // Buscamos os atletas ordenando pela maior pontuação (Ranking) e adicionando a data_nascimento
        $listAtletas->fullRead("SELECT usr.id, usr.empresa_id, emp.razao_social AS razao_social, emp.nome_fantasia AS nome_fantasia, 
                                       usr.name, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, 
                                       usr.adms_access_level_id, usr.adms_sits_user_id, usr.data_nascimento   
                               FROM adms_users AS usr
                               INNER JOIN adms_emp_principal AS emp ON emp.id = usr.empresa_id 
                               WHERE usr.adms_access_level_id= :nivel AND usr.adms_sits_user_id= :situacao AND usr.empresa_id= :empresa_id_usr
                               ORDER BY usr.pontuacao_ranking DESC", "nivel=14&situacao=1&empresa_id_usr={$_SESSION['emp_user']}");

        $this->result = $listAtletas->getResult();
    }
}