<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar marcas de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddMarca
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar marcas de equipamentos
     * Receber os dados do formulário.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddMarca'])) {
            unset($this->dataForm['SendAddMarca']);
            $createMarca = new \App\adms\Models\AdmsAddMarca();
            $createMarca->create($this->dataForm);
            if ($createMarca->getResult()) {
                $urlRedirect = URLADM . "list-marca/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddMarca();
            }
        } else {
            $this->viewAddMarca();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddMarca(): void
    {
        $button = ['list_marca' => ['menu_controller' => 'list-marca', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-marca"; 
        
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/addMarca", $this->data);
        $loadView->loadView();
    }
}
