<?php

namespace App\adms\Models\helper;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe 
 *
  * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsButton
{

    /** @var array $result Recebe os registros do banco de dados e retorna para a Models */
    private array|null $result = null;

    /** @var array|null $data Recebe o array de dados */
    private array|null $data;

    /**
     * @return array Retorna o array de dados
     */
    function getResult(): array|null
    {
        return $this->result;
    }

    /** 
     * Retornar as paginas / botoes que o nivel de acesso em permissao de acessar.
     * Converte a parseString de string para array.
     * @param array|null $data Recebe o array de botoes para verificar se o nivel de acesso tem permissao de acessar as paginas
     * 
     * @return array|null
     */
    public function buttonPermission(array|null $data): array|null
    {
        $this->data = $data;

        foreach ($this->data as $key => $button) {
            extract($button);
            $viewButton = new \App\adms\Models\helper\AdmsRead();
            $viewButton->fullRead("SELECT pag.id FROM adms_pages pag
                            INNER JOIN adms_levels_pages AS lev_pag ON lev_pag.adms_page_id=pag.id
                            WHERE pag.menu_controller =:menu_controller
                            AND pag.menu_metodo =:menu_metodo
                            AND lev_pag.permission = 1
                            AND lev_pag.adms_access_level_id =:adms_access_level_id
                            LIMIT :limit",
                "menu_controller=$menu_controller&menu_metodo=$menu_metodo&adms_access_level_id=" . $_SESSION['adms_access_level_id'] . "&limit=1"
            );
            if ($viewButton->getResult()) {
                $this->result[$key] = true;
            } else {
                $this->result[$key] = false;
            }
        }
        return $this->result;
    }
}
