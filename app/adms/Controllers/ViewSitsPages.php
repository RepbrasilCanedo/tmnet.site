<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar situação páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewSitsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar situação páginas
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewSitsPages
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar situação de páginas.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewSitPages = new \App\adms\Models\AdmsViewSitsPages();
            $viewSitPages->viewSitPages($this->id);
            if ($viewSitPages->getResult()) {
                $this->data['viewSitPages'] = $viewSitPages->getResultBd();
                $this->viewSitPages();
            } else {
                $urlRedirect = URLADM . "list-sits-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Situação de página não encontrada!</p>";
            $urlRedirect = URLADM . "list-sits-pages/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewSitPages(): void
    {
        $button = ['list_sits_pages' => ['menu_controller' => 'list-sits-pages', 'menu_metodo' => 'index'],
        'edit_sits_pages' => ['menu_controller' => 'edit-sits-pages', 'menu_metodo' => 'index'],
        'delete_sits_pages' => ['menu_controller' => 'delete-sits-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-sits-pages";
        $loadView = new \Core\ConfigView("adms/Views/sitsPages/viewSitPages", $this->data);
        $loadView->loadView();
    }
}
