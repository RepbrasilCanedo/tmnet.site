<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar setor
 * @author Daniel Canedo <docan2006@gmail.com>
 */
class AddSetor
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar setor
     * Receber os dados do formulário.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página novo setor. Acessa o IF e instância a classe "AdmsAddSetor" responsável em cadastrar o setor no banco de dados.
     * Situação cadastrada com sucesso, redireciona para a página listar setores.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        //echo "<pre>"; var_dump($this->dataForm );

        if (!empty($this->dataForm['SendAddSetor'])) {
            unset($this->dataForm['SendAddSetor']);
            $createSetor = new \App\adms\Models\AdmsAddSetor();
            $createSetor->create($this->dataForm);
            if ($createSetor->getResult()) {
                $urlRedirect = URLADM . "list-setor/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddSetor();
            }
        } else {
            $this->viewAddSetor();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddSetor(): void
    {
        $button = ['list_setor' => ['menu_controller' => 'list-setor', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddSetor();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-setor"; 
        
        $loadView = new \Core\ConfigView("adms/Views/empresas/addSetor", $this->data);
        $loadView->loadView();
    }
}
