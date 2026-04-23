<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar o usuário no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewUsers
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

    public function viewUser(int $id): void
    {
        $this->id = $id;

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        
        // 1. BUSCA OS DADOS PRINCIPAIS DO USUÁRIO
        $viewUser->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.data_nascimento, usr.email, usr.telefone, 
                            usr.user, usr.imagem, usr.created, usr.modified, sit.name AS name_sit, col.color, lev.id AS id_lev, lev.name AS name_lev, 
                            emp.nome_fantasia as nome_fantasia_emp, emp.razao_social as razao_social_emp
                            FROM adms_users AS usr
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id 
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                            WHERE usr.id= :id_user 
                            LIMIT :limit", "id_user={$this->id}&limit=1");

        if ($viewUser->getResult()) {
            $this->resultBd = $viewUser->getResult();
            
            // DOCAN ENGINE: Se for um Atleta (Nível 14), vamos buscar a sua Carreira!
            if ($this->resultBd[0]['id_lev'] == 14) {
                
                // A) Buscar todos os Clubes aos quais ele está filiado
                $readClubes = new \App\adms\Models\helper\AdmsRead();
                $readClubes->fullRead(
                    "SELECT emp.nome_fantasia, emp.logo 
                     FROM adms_atleta_clube ac 
                     INNER JOIN adms_emp_principal emp ON emp.id = ac.empresa_id 
                     WHERE ac.adms_user_id = :id_user", 
                    "id_user={$this->id}"
                );
                $this->resultBd['clubes_filiado'] = $readClubes->getResult() ?: [];

                // B) Buscar Histórico e Próximos Torneios
                $readTorneios = new \App\adms\Models\helper\AdmsRead();
                $readTorneios->fullRead(
                    "SELECT c.id, c.nome_torneio, c.data_evento, cat.nome as nome_categoria, i.status_pagamento_id 
                     FROM adms_inscricoes i 
                     INNER JOIN adms_competicoes c ON c.id = i.adms_competicao_id 
                     INNER JOIN adms_categorias cat ON cat.id = i.adms_categoria_id 
                     WHERE i.adms_user_id = :id_user 
                     ORDER BY c.data_evento DESC", 
                    "id_user={$this->id}"
                );
                
                $torneios = $readTorneios->getResult() ?: [];
                $this->resultBd['torneios_ativos'] = [];
                $this->resultBd['torneios_historico'] = [];
                $hoje = date('Y-m-d');
                
                foreach ($torneios as $t) {
                    if ($t['data_evento'] >= $hoje) {
                        $this->resultBd['torneios_ativos'][] = $t;
                    } else {
                        $this->resultBd['torneios_historico'][] = $t;
                    }
                }
            }

            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $this->result = false;
        }
    }
}