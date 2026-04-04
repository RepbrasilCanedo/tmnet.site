<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar Marca
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewMarca
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar marca
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewMarca
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar marcas.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewMarca = new \App\adms\Models\AdmsViewMarca();
            $viewMarca->viewMarca($this->id);
            if ($viewMarca->getResult()) {
                $this->data['viewMarca'] = $viewMarca->getResultBd();
                $this->viewMarca();
            } else {
                $urlRedirect = URLADM . "list-marca/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cor não encontrada!</p>";
            $urlRedirect = URLADM . "list-marca/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewMarca(): void
    {
        $button = ['list_marca' => ['menu_controller' => 'list-marca', 'menu_metodo' => 'index'],
        'edit_marca' => ['menu_controller' => 'edit-marca', 'menu_metodo' => 'index'],
        'delete_marca' => ['menu_controller' => 'delete-marca', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-marca"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/viewMarca", $this->data);
        $loadView->loadView();
    }
}
