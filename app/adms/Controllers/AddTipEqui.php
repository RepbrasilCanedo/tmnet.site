<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar tipo de equipamanto
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddTipEqui
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar tipo de equipamento
     * Receber os dados do formulário.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página nova cor. Acessa o IF e instância a classe "AdmsAddColores" responsável em cadastrar a situação no banco de dados.
     * Situação cadastrada com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddTipEqui'])) {
            unset($this->dataForm['SendAddTipEqui']);
            $createTipEqui = new \App\adms\Models\AdmsAddTipEqui();
            $createTipEqui->create($this->dataForm);
            if ($createTipEqui->getResult()) {
                $urlRedirect = URLADM . "list-tip-equi/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddTipEqui();
            }
        } else {
            $this->viewAddTipEqui();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddTipEqui(): void
    {
        $button = ['list_tip_equi' => ['menu_controller' => 'list-tip-equi', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $listSelect = new \App\adms\Models\AdmsAddTipEqui();
        $this->data['select'] = $listSelect->listSelect();
        
        $this->data['sidebarActive'] = "list-tip-equi"; 
        
        $loadView = new \Core\ConfigView("adms/Views/produtos/addTipEqui", $this->data);
        $loadView->loadView();
    }
}
