<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar usuarios final
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewUsersFinal
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar usuarios
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewUsers
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar usuario.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewUserFinal = new \App\adms\Models\AdmsViewUsersFinal();
            $viewUserFinal->viewUserFinal($this->id);
            if ($viewUserFinal->getResult()) {
                $this->data['viewUserFinal'] = $viewUserFinal->getResultBd();
                $this->viewUserFinal();
            } else {
                $urlRedirect = URLADM . "list-users-final/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário Final não encontrado!</p>";
            $urlRedirect = URLADM . "list-users-final/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewUserFinal(): void
    {
        $button = ['list_users_final' => ['menu_controller' => 'list-users-final', 'menu_metodo' => 'index'],
        'edit_users_final' => ['menu_controller' => 'edit-users-final', 'menu_metodo' => 'index'],
        'edit_users_password_final' => ['menu_controller' => 'edit-users-password-final', 'menu_metodo' => 'index'],
        'edit_users_image_final' => ['menu_controller' => 'edit-users-image-final', 'menu_metodo' => 'index'],
        'delete_users_final' => ['menu_controller' => 'delete-users-final', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-users-final"; 
        $loadView = new \Core\ConfigView("adms/Views/users/viewUserFinal", $this->data);
        $loadView->loadView();
    }
}
