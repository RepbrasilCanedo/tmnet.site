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
class AdmsViewUsersFinal
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
    public function viewUserFinal(int $id): void
    {
        $this->id = $id;

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT usr_final.id, usr_final.name AS name_usr_final, usr_final.nickname AS nickname_usr_final,
                            usr_final.email AS email_usr_final, usr_final.tel_1 AS tel_1_usr_final, usr_final.user AS user_usr_final, usr_final.image, 
                            sit.name AS name_sit, lev.name AS name_lev, usr_final.empresa_id  AS empresa_id_usr_final, col.color AS color_col, emp.razao_social  AS razao_social_emp, clie.nome_fantasia  AS nome_fantasia_clie, usr_final.created AS created_usr_final, usr_final.modified AS modified_usr_final 
                            FROM adms_users_final as usr_final
                            INNER JOIN adms_sits_users AS sit ON sit.id=usr_final.adms_sits_user_id
                            INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id
                            INNER JOIN adms_emp_principal AS emp ON emp.id=usr_final.empresa_id                             
                            INNER JOIN adms_clientes AS clie ON clie.id=usr_final.cliente_id 
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr_final.adms_access_level_id
                            WHERE usr_final.id= :id_usr LIMIT :limit","id_usr={$this->id}&limit=1");


        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário Final não encontrado!</p>";
            $this->result = false;
        }
    }
}
