<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar grupos de página
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewGroupsPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar grupos de página
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewGroupsPages
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar grupos de página
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewGroupsPages = new \App\adms\Models\AdmsViewGroupsPages();
            $viewGroupsPages->viewGroupsPages($this->id);
            if ($viewGroupsPages->getResult()) {
                $this->data['viewGroupsPages'] = $viewGroupsPages->getResultBd();
                $this->viewGroupsPages();
            } else {
                $urlRedirect = URLADM . "list-groups-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Grupo de página não encontrado!</p>";
            $urlRedirect = URLADM . "list-groups-pages/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewGroupsPages(): void
    {
        $button = ['list_groups_pages' => ['menu_controller' => 'list-groups-pages', 'menu_metodo' => 'index'],
        'edit_groups_pages' => ['menu_controller' => 'edit-groups-pages', 'menu_metodo' => 'index'],
        'delete_groups_pages' => ['menu_controller' => 'delete-groups-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-groups-pages";
        $loadView = new \Core\ConfigView("adms/Views/groupsPages/viewGroupsPages", $this->data);
        $loadView->loadView();
    }
}
