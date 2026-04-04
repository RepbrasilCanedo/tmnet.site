<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar imagem do usuário
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditUsersImageFinal
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar imagem do usuário.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações do usuário no banco de dados, se encontrar instancia o método "viewEditUserImage". Se não existir redireciona para o listar usuários.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editUserImage".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditUserImageFinal']))) {
            $this->id = (int) $id;
            $viewUserClieFin = new \App\adms\Models\AdmsEditUsersImageFinal();
            $viewUserClieFin->viewUserClieFin($this->id);
            if ($viewUserClieFin->getResult()) {
                $this->data['form'] = $viewUserClieFin->getResultBd();
                $this->viewEditUserImageClieFin();
            } else {
                $urlRedirect = URLADM . "list-users-final/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editUserImageClieFin();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditUserImageClieFin(): void
    {
        $button = ['list_users_final' => ['menu_controller' => 'list-users-final', 'menu_metodo' => 'index'],
        'view_users_final' => ['menu_controller' => 'view-users-final', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-users-final"; 
        $loadView = new \Core\ConfigView("adms/Views/users/editUsersImageFinal", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar imagem do usuario.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o usuário no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar usuarios.
     *
     * @return void
     */
    private function editUserImageClieFin(): void
    {
        if (!empty($this->dataForm['SendEditUserImageFinal'])) {
            unset($this->dataForm['SendEditUserImageFinal']);
            $this->dataForm['new_image_clie_fin'] = $_FILES['new_image_clie_fin'] ? $_FILES['new_image_clie_fin'] : null;
            $editUserImage = new \App\adms\Models\AdmsEditUsersImageFinal();
            $editUserImage->update($this->dataForm);
            if ($editUserImage->getResult()) {
                $urlRedirect = URLADM . "view-users-final/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditUserImageClieFin();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário final não encontrado!</p>";
            $urlRedirect = URLADM . "list-users-final/index";
            header("Location: $urlRedirect");
        }
    }
}
