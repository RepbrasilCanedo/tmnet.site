<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewItemMenu
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar item de menu
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewItemMenu
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar item de menu.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewItemMenu = new \App\adms\Models\AdmsViewItemMenu();
            $viewItemMenu->viewItemMenu($this->id);
            if ($viewItemMenu->getResult()) {
                $this->data['viewItemMenu'] = $viewItemMenu->getResultBd();
                $this->viewItemMenu();
            } else {
                $urlRedirect = URLADM . "list-item-menu/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $urlRedirect = URLADM . "list-item-menu/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewItemMenu(): void
    {
        $button = ['list_item_menu' => ['menu_controller' => 'list-item-menu', 'menu_metodo' => 'index'],
        'edit_item_menu' => ['menu_controller' => 'edit-item-menu', 'menu_metodo' => 'index'],
        'delete_item_menu' => ['menu_controller' => 'delete-item-menu', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-item-menu"; 
        $loadView = new \Core\ConfigView("adms/Views/itemMenu/viewItemMenu", $this->data);
        $loadView->loadView();
    }
}
