<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar usuário final
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditUsersFinal
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar usuário.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações do usuário no banco de dados, se encontrar instancia o método "viewEditUser". Se não existir redireciona para o listar usuários.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editUser".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditUserFinal']))) {
            $this->id = (int) $id;
            $viewUserFinal = new \App\adms\Models\AdmsEditUsersFinal();
            $viewUserFinal->viewUserFinal($this->id);
            if ($viewUserFinal->getResult()) {
                $this->data['form'] = $viewUserFinal->getResultBd();
                $this->viewEditUserFinal();
            } else {
                $urlRedirect = URLADM . "list-users-final/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editUserFinal();
        }
    }

    /**
     * Instanciar a MODELS e o método "listSelect" responsável em buscar os dados para preencher o campo SELECT 
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditUserFinal(): void
    {
        $button = ['list_users_final' => ['menu_controller' => 'list-users-final', 'menu_metodo' => 'index'],
        'view_users_final' => ['menu_controller' => 'view-users-final', 'menu_metodo' => 'index']];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
                
        $listSelect = new \App\adms\Models\AdmsEditUsers();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-users-final"; 
        $loadView = new \Core\ConfigView("adms/Views/users/editUserFinal", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar usuario.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o usuário no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar usuarios.
     *
     * @return void
     */
    private function editUserFinal(): void
    {
        if (!empty($this->dataForm['SendEditUserFinal'])) {
            unset($this->dataForm['SendEditUserFinal']);
            $editUser = new \App\adms\Models\AdmsEditUsersFinal();
            $editUser->update($this->dataForm);
            
            if($editUser->getResult()){
                $urlRedirect = URLADM . "view-users-final/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewEditUserFinal();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário Final não encontrado!</p>";
            $urlRedirect = URLADM . "list-users-final/index";
            header("Location: $urlRedirect");
        }
    }
}
