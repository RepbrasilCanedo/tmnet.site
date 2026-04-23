<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar usuarios
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewUsers
{
    private array|string|null $data;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        // ========================================================================
        // DOCAN FIX: O PULO DO GATO (AUTO-ID)
        // Se o atleta clicar no menu sem ID na URL, o sistema assume o ID dele!
        // ========================================================================
        if (empty($id) && isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
        }

        if (!empty($id)) {
            $this->id = (int) $id;
            
            // TRAVA DE SEGURANÇA (O Atleta só pode ver o seu próprio perfil)
            if ($_SESSION['adms_access_level_id'] == 14 && $this->id != $_SESSION['user_id']) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Você não tem permissão para aceder ao perfil de outro atleta.</p>";
                header("Location: " . URLADM . "dashboard/index");
                exit;
            }

            $viewUser = new \App\adms\Models\AdmsViewUsers();
            $viewUser->viewUser($this->id);
            
            if ($viewUser->getResult()) {
                $this->data['viewUser'] = $viewUser->getResultBd();
                $this->viewUser();
            } else {
                $urlRedirect = URLADM . "list-users/index";
                header("Location: $urlRedirect");
                exit;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $urlRedirect = URLADM . "list-users/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    private function viewUser(): void
    {
        $button = [
            'list_users' => ['menu_controller' => 'list-users', 'menu_metodo' => 'index'],
            'edit_users' => ['menu_controller' => 'edit-users', 'menu_metodo' => 'index'],
            'edit_users_password' => ['menu_controller' => 'edit-users-password', 'menu_metodo' => 'index'],
            'edit_users_image' => ['menu_controller' => 'edit-users-image', 'menu_metodo' => 'index'],
            'delete_users' => ['menu_controller' => 'delete-users', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-users"; 
        $loadView = new \Core\ConfigView("adms/Views/users/viewUser", $this->data);
        $loadView->loadView();
    }
}