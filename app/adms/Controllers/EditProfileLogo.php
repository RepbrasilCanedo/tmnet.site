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

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar imagem do perfil.
     * Receber os dados do formulário.
     * 
     * Quando o usuário clicar no botão "editar" do formulário da página editar imagem do perfil. Acessa o IF e instância o método "AdmsEditProfileImage".
     * Senão, instancia a MODELS e recupera os dados do perfil do usuário no banco de dados.
     * 
     * Existindo o usuário no banco de dados, recebe os dados do perfil e instancia o método viewEditProfImagem.
     * Senão redireciona o usuário para página de login
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {

        $this->id = (int) $id;
        $_SESSION['emp_logo']='';
        $_SESSION['emp_logo'] = $this->id;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);  

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
            }
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
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

    /**
     * Editar imagem do perfil.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o perfil no banco de dados.
     * Se o usuário não clicou no botão redireciona para página de login.
     *
     * @return void
     */
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
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfLogo();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Logo não encontrado!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
