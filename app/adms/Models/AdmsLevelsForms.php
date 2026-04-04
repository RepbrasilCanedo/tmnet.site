<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Recuperar nivel de acesso para o formulario novo usuario na pagina de login no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsLevelsForms
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

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
     * Metodo para visualziar nivel de acesso para o formulario novo usuario na pagina de login
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    public function viewLevelsForms(): void
    {
        $viewLevelsForm = new \App\adms\Models\helper\AdmsRead();
        $viewLevelsForm->fullRead("SELECT lev_form.id, lev_form.created, lev_form.modified, 
                    lev.name name_lel,
                    sit.name name_sit,
                    col.color
                    FROM adms_levels_forms AS lev_form 
                    INNER JOIN adms_access_levels AS lev ON lev.id=lev_form.adms_access_level_id 
                    INNER JOIN adms_sits_users AS sit ON sit.id=lev_form.adms_sits_user_id  
                    INNER JOIN adms_colors AS col ON col.id=sit.adms_color_id  
                    LIMIT :limit", "limit=1");

        $this->resultBd = $viewLevelsForm->getResult();        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página de configuração não encontrada!</p>";
            $this->result = false;
        }
    }
}
