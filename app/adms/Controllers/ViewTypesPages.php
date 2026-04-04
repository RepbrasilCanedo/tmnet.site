<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar tipos de página
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewTypesPages
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar tipos de página
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewTypesPages
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar tipos de página
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewTypesPages = new \App\adms\Models\AdmsViewTypesPages();
            $viewTypesPages->viewTypesPages($this->id);
            if ($viewTypesPages->getResult()) {
                $this->data['viewTypesPages'] = $viewTypesPages->getResultBd();
                $this->viewTypesPages();
            } else {
                $urlRedirect = URLADM . "list-types-pages/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipos de página não encontrado!</p>";
            $urlRedirect = URLADM . "list-types-pages/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewTypesPages(): void
    {
        $button = ['list_types_pages' => ['menu_controller' => 'list-types-pages', 'menu_metodo' => 'index'],
        'edit_types_pages' => ['menu_controller' => 'edit-types-pages', 'menu_metodo' => 'index'],
        'delete_types_pages' => ['menu_controller' => 'delete-types-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-types-pages";
        $loadView = new \Core\ConfigView("adms/Views/typesPages/viewTypesPages", $this->data);
        $loadView->loadView();
    }
}
