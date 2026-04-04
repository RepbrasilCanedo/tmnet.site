<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditItemMenu
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar item de menu.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações do item de menu no banco de dados, se encontrar instancia o método "viewEditItemMenu". Se não existir redireciona para o listar item de menu.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editItemMenu".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditItemMenu']))) {
            $this->id = (int) $id;
            $viewItemMenu = new \App\adms\Models\AdmsEditItemMenu();
            $viewItemMenu->viewItemMenu($this->id);
            if ($viewItemMenu->getResult()) {
                $this->data['form'] = $viewItemMenu->getResultBd();
                $this->viewEditItemMenu();
            } else {
                $urlRedirect = URLADM . "list-item-menu/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editItemMenu();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditItemMenu(): void
    {
        $button = ['list_item_menu' => ['menu_controller' => 'list-item-menu', 'menu_metodo' => 'index'],
        'view_item_menu' => ['menu_controller' => 'view-item-menu', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-item-menu"; 
        $loadView = new \Core\ConfigView("adms/Views/itemMenu/editItemMenu", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar item de menu.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente item de menu no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar item de menu.
     *
     * @return void
     */
    private function editItemMenu(): void
    {
        if (!empty($this->dataForm['SendEditItemMenu'])) {
            unset($this->dataForm['SendEditItemMenu']);
            $editItemMenu = new \App\adms\Models\AdmsEditItemMenu();
            $editItemMenu->update($this->dataForm);
            if ($editItemMenu->getResult()) {
                $urlRedirect = URLADM . "view-item-menu/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditItemMenu();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Iem de menu não encontrado!</p>";
            $urlRedirect = URLADM . "list-item-menu/index";
            header("Location: $urlRedirect");
        }
    }
}
