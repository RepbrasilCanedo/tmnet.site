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
class AdmsMenu
{

    /** @var array|null $resultBd Recebe os registros do banco de dados e retorna para a Controller */
    private array|null $resultBd;

    /**      
     * Pesquisa no banco de dados os itens de menu do nivel de acesso do usuario logado
     * @return array|null|bool Retorna os itens de menu
     */
    public function itemMenu(): array|null|bool
    {
        $listMenu = new \App\adms\Models\helper\AdmsRead();
        $listMenu->fullRead(
            "SELECT lev_pag.id AS id_lev_pag, lev_pag.adms_page_id,
                        pag.id AS id_pag, pag.menu_controller, pag.menu_metodo, pag.name_page, pag.icon
                        FROM adms_levels_pages AS lev_pag
                        INNER JOIN adms_pages AS pag ON pag.id=lev_pag.adms_page_id
                        WHERE ((lev_pag.adms_access_level_id =:adms_access_level_id) 
                        AND (lev_pag.permission = 1))
                        AND print_menu = 1
                        ORDER BY lev_pag.order_level_page ASC",
            "adms_access_level_id=" . $_SESSION['adms_access_level_id']
        );

        $this->resultBd = $listMenu->getResult();
        if ($this->resultBd) {
            return $this->resultBd;
        } else {
            return false;
        }
    }
}
