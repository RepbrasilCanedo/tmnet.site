<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddItemMenu
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Motodo cadastrar item de menu
     * Receber os dados do formulario.
     * Quando o usuario clicar no botao "cadastrar" do formulario da pagina novo item de menu. Acessa o IF e instância a classe "AdmsAddItemMenu" responsavel em cadastrar o item de menu no banco de dados.
     * Item de menu cadastrado com sucesso, redireciona para a pagina listar registros.
     * Senao, instância a classe responsavel em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddItemMenu'])) {
            unset($this->dataForm['SendAddItemMenu']);
            $createItemMenu = new \App\adms\Models\AdmsAddItemMenu();
            $createItemMenu->create($this->dataForm);
            if ($createItemMenu->getResult()) {
                $urlRedirect = URLADM . "list-item-menu/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddItemMenu();
            }
        } else {
            $this->viewAddItemMenu();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddItemMenu(): void
    {
        $button = ['list_item_menu' => ['menu_controller' => 'list-item-menu', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-item-menu"; 
        
        $loadView = new \Core\ConfigView("adms/Views/itemMenu/addItemMenu", $this->data);
        $loadView->loadView();
    }
}
