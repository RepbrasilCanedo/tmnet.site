<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da pagina visualizar nivel de acesso
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewAccessLevels
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar nivel de acesso
     * Recebe como parametro o ID que sera usado para pesquisar as informacoes no banco de dados e instancia a MODELS AdmsViewAccessLevels
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senao he redirecionado para o listar nivel de acesso.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewAccessLevels = new \App\adms\Models\AdmsViewAccessLevels();
            $viewAccessLevels->viewAccessLevels($this->id);
            if ($viewAccessLevels->getResult()) {
                $this->data['viewAccessLevels'] = $viewAccessLevels->getResultBd();
                $this->viewAccessLevels();
            } else {
                $urlRedirect = URLADM . "list-access-levels/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acessi não encontrado!</p>";
            $urlRedirect = URLADM . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAccessLevels(): void
    {
        $button = ['list_access_levels' => ['menu_controller' => 'list-access-levels', 'menu_metodo' => 'index'],
        'edit_access_levels' => ['menu_controller' => 'edit-access-levels', 'menu_metodo' => 'index'],
        'delete_access_levels' => ['menu_controller' => 'delete-access-levels', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-access-levels"; 
        $loadView = new \Core\ConfigView("adms/Views/accessLevels/viewAccessLevels", $this->data);
        $loadView->loadView();
    }
}
