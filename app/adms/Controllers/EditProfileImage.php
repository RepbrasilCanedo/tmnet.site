<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar imagem do perfil
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditProfileImage
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendEditProfImage'])) {
           $this->editProfImage();
        } else {
            $viewProfImg = new \App\adms\Models\AdmsEditProfileImage();
            $viewProfImg->viewProfile();
            if ($viewProfImg->getResult()) {
                $this->data['form'] = $viewProfImg->getResultBd();
                $this->viewEditProfImagem();
            } else {
                $urlRedirect = URLADM . "login/index";
                header("Location: $urlRedirect");
                exit; // DOCAN FIX: Trava de redirecionamento
            }
        }
    }

    private function viewEditProfImagem(): void
    {
        $button = ['view_profile' => ['menu_controller' => 'view-profile', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $loadView = new \Core\ConfigView("adms/Views/users/editProfileImage", $this->data);
        $loadView->loadView();
    }

    private function editProfImage(): void
    {
        if (!empty($this->dataForm['SendEditProfImage'])) {
            unset($this->dataForm['SendEditProfImage']);
            $this->dataForm['new_image'] = $_FILES['new_image'] ? $_FILES['new_image'] : null;
            $editProfImg = new \App\adms\Models\AdmsEditProfileImage();
            $editProfImg->update($this->dataForm);
            
            if ($editProfImg->getResult()) {
                $urlRedirect = URLADM . "view-profile/index";
                header("Location: $urlRedirect");
                exit; // DOCAN FIX: Trava de redirecionamento
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfImagem();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
            exit; // DOCAN FIX: Trava de redirecionamento
        }
    }
}