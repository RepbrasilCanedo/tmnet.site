<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar Modelo
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewModelo
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar Modelo
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewModelo
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar modelos.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewModelo = new \App\adms\Models\AdmsViewModelo();
            $viewModelo->viewModelo($this->id);
            if ($viewModelo->getResult()) {
                $this->data['viewModelo'] = $viewModelo->getResultBd();
                $this->viewModelo();
            } else {
                $urlRedirect = URLADM . "list-modelo/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não encontrada!</p>";
            $urlRedirect = URLADM . "list-modelo/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewModelo(): void
    {
        $button = ['list_modelo' => ['menu_controller' => 'list-modelo', 'menu_metodo' => 'index'],
        'edit_modelo' => ['menu_controller' => 'edit-modelo', 'menu_metodo' => 'index'],
        'delete_modelo' => ['menu_controller' => 'delete-modelo', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-modelo"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/viewModelo", $this->data);
        $loadView->loadView();
    }
}
