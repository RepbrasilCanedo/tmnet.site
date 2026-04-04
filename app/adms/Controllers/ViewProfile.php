<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar perfil
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewProfile
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /**
     * Metodo visualizar perfil
     * Instancia a MODELS AdmsViewProfile para pesquisar as informações do usuário
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para a página de login.
     * 
     * @return void
     */
    public function index(): void
    {
        $viewProfile = new \App\adms\Models\AdmsViewProfile();
        $viewProfile->viewProfile();
        if ($viewProfile->getResult()) {
            $this->data['viewProfile'] = $viewProfile->getResultBd();
            $this->loadViewProfile();
        } else {
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function loadViewProfile(): void
    {
        $button = ['edit_profile' => ['menu_controller' => 'edit-profile', 'menu_metodo' => 'index'],
        'edit_profile_password' => ['menu_controller' => 'edit-profile-password', 'menu_metodo' => 'index'],
        'edit_profile_image' => ['menu_controller' => 'edit-profile-image', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $loadView = new \Core\ConfigView("adms/Views/users/viewProfile", $this->data);
        $loadView->loadView();
    }
}
