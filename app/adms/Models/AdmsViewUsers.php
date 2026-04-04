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

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo para visualizar os detalhes do usuário
     * Recebe o ID do usuário que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro
     * @param integer $id
     * @return void
     */
    public function viewUser(int $id): void
    {
        $this->id = $id;

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT usr.id, usr.name AS name_usr, usr.apelido, usr.estilo_jogo, usr.mao_dominante, usr.pontuacao_ranking, usr.data_nascimento, usr.email, usr.tel_1, usr.tel_2, 
                            usr.user, usr.imagem, usr.created, usr.modified, sit.name AS name_sit, col.color, lev.id AS id_lev, lev.name AS name_lev, 
                            emp.nome_fantasia as nome_fantasia_emp, emp.razao_social as razao_social_emp
                            FROM adms_users AS usr
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id 
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                            WHERE usr.id= :id_user 
                            LIMIT :limit", "id_user={$this->id}&limit=1");

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $this->result = false;
        }
    }
}
