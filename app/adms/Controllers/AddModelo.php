<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar modelos de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddModelo
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar modelos de equipamentos
     * Receber os dados do formulário.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddModelo'])) {
            unset($this->dataForm['SendAddModelo']);
            $createModelo = new \App\adms\Models\AdmsAddModelo();
            $createModelo->create($this->dataForm);
            if ($createModelo->getResult()) {
                $urlRedirect = URLADM . "list-modelo/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddModelo();
            }
        } else {
            $this->viewAddModelo();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddModelo(): void
    {
        $button = ['list_modelo' => ['menu_controller' => 'list-modelo', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-modelo"; 
        
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/addModelo", $this->data);
        $loadView->loadView();
    }
}
