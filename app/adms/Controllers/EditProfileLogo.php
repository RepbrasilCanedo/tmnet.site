<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar Logo do contrato
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditProfileLogo
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);  

        // =========================================================================
        // DOCAN FIX BLINDADO: Gestão Inteligente do ID da Empresa
        // Não destrói a sessão ao salvar. Garante que a imagem vai para a pasta certa!
        // =========================================================================
        if (!empty($id)) {
            // Se veio um ID na URL, atualiza a sessão (Admin a editar outro clube)
            $_SESSION['emp_logo'] = (int)$id;
        } elseif (empty($_SESSION['emp_logo']) && !empty($_SESSION['emp_user'])) {
            // Se não veio ID e a sessão está vazia, assume que é o próprio clube logado
            $_SESSION['emp_logo'] = (int)$_SESSION['emp_user'];
        }

        if (!empty($this->dataForm['SendEditProfLogo'])) {
           $this->editProfLogo();
        } else {
            $viewProfImg = new \App\adms\Models\AdmsEditProfileLogo();
            $viewProfImg->viewProfileLogo();
            
            if ($viewProfImg->getResult()) {
                $this->data['form'] = $viewProfImg->getResultBd();
                $this->viewEditProfLogo();
            } else {
                $urlRedirect = URLADM . "list-emp-principal/index";
                header("Location: $urlRedirect");
                exit;
            }
        }
    }

    private function viewEditProfLogo(): void
    {
        $button = ['view_profile' => ['menu_controller' => 'view-profile', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();

        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $loadView = new \Core\ConfigView("adms/Views/empresas/editProfileLogo", $this->data);
        $loadView->loadView();
    }

    private function editProfLogo(): void
    {
        if (!empty($this->dataForm['SendEditProfLogo'])) {
            unset($this->dataForm['SendEditProfLogo']);

            $this->dataForm['new_image'] = $_FILES['new_image'] ? $_FILES['new_image'] : null;
            $editProfImg = new \App\adms\Models\AdmsEditProfileLogo();
            $editProfImg->update($this->dataForm);
            
            if ($editProfImg->getResult()) {
                $urlRedirect = URLADM . "list-emp-principal/index";
                header("Location: $urlRedirect");
                exit;
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfLogo();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Logo não encontrada!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
            exit;
        }
    }
}