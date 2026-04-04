<?php

namespace app\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar imagem do chamado
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditProfileImageOrcam 
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
       

        $this->dataForm = filter_input_array(INPUT_POST,FILTER_DEFAULT);
        $this->id = (int) $id;

        $_SESSION['set_orcam']='';
        $_SESSION['set_orcam'] = $this->id;

        if (!empty($this->dataForm['SendEditProfImageOrcam'])) {
           $this->editProfImageOrcam();
        } else {
            $viewProfImgOrcam = new \App\adms\Models\AdmsEditProfileImageOrcam();
            $viewProfImgOrcam->viewProfileOrcam($this->id);

            if ($viewProfImgOrcam->getResult()) {
                $this->data['form'] = $viewProfImgOrcam->getResultBd();
                $this->viewEditProfImagemOrcam();
            } else {
                $urlRedirect = URLADM . "list-orcam/index";
                header("Location: $urlRedirect");
            }
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditProfImagemOrcam(): void
    {
        $button = ['list_orcam' => ['menu_controller' => 'list-orcam', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $loadView = new \Core\ConfigView("adms/Views/orcamentos/editProfileImageOrcam", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar anexo do chamado.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a imagem no banco de dados.
     * Se o usuário não clicou no botão redireciona para página de login.
     *
     * @return void
     */
    private function editProfImageOrcam(): void
    {
        
        if (!empty($this->dataForm['SendEditProfImageOrcam'])) {
            unset($this->dataForm['SendEditProfImageOrcam']);

            $this->dataForm['new_image_orcam']=$_FILES['new_image_orcam']?$_FILES['new_image_orcam']:null;
        
            $editProfImgOrcam = new \App\adms\Models\AdmsEditProfileImageOrcam();
            $editProfImgOrcam->update($this->dataForm);

            if ($editProfImgOrcam->getResult()) {
                $urlRedirect = URLADM . "view-orcam/index/".$_SESSION['set_orcam'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfImagemOrcam();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Orçamento não encontrado!</p>";
            $urlRedirect = URLADM . "list-orcam/index";
            header("Location: $urlRedirect");
        }
            
    
        }
}
