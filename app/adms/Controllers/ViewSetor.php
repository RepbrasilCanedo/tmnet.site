<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar setor
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewSetor
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar setor
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewSetor
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar Setor.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewSetor = new \App\adms\Models\AdmsViewSetor();
            $viewSetor->viewSetor($this->id);
            if ($viewSetor->getResult()) {
                $this->data['viewSetor'] = $viewSetor->getResultBd();
                $this->viewSetor();
            } else {
                $urlRedirect = URLADM . "list-setor/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor não encontrada!</p>";
            $urlRedirect = URLADM . "list-setor/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewSetor(): void
    {
        $button = ['list_setor' => ['menu_controller' => 'list-setor', 'menu_metodo' => 'index'],
        'edit_setor' => ['menu_controller' => 'edit-setor', 'menu_metodo' => 'index'],
        'delete_setor' => ['menu_controller' => 'delete-setor', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-setor"; 
        $loadView = new \Core\ConfigView("adms/Views/empresas/viewSetor", $this->data);
        $loadView->loadView();
    }
}
