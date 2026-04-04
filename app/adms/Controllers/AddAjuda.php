<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar Tutoriais de ajuda do sistema
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddAjuda
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar Tutoriais
     * Receber os dados do formulário.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página AddAjuda. Acessa o IF e instância a classe "AdmsAddAjuda" responsável em cadastrar a situação no banco de dados.
     * Situação cadastrada com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddColor'])) {
            unset($this->dataForm['SendAddColor']);
            $createColor = new \App\adms\Models\AdmsAddColors();
            $createColor->create($this->dataForm);
            if ($createColor->getResult()) {
                $urlRedirect = URLADM . "list-colors/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddColor();
            }
        } else {
            $this->viewAddColor();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddColor(): void
    {
        $button = ['list_colors' => ['menu_controller' => 'list-colors', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-colors"; 
        
        $loadView = new \Core\ConfigView("adms/Views/colors/addColors", $this->data);
        $loadView->loadView();
    }
}
