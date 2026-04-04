<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar Tipos de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddTypeEquip
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**     
     * Método cadastrar tipos de equipamentos
     * Receber os dados do formulário.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendAddTypeEquip'])) {
            unset($this->dataForm['SendAddTypeEquip']);
            $createTypeEquip = new \App\adms\Models\AdmsAddTypeEquip();
            $createTypeEquip->create($this->dataForm);
            if ($createTypeEquip->getResult()) {
                $urlRedirect = URLADM . "list-type-equip/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewAddTypeEquip();
            }
        } else {
            $this->viewAddTypeEquip();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddTypeEquip(): void
    {
        $button = ['list_type_equip' => ['menu_controller' => 'list-type-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-type-equip"; 
        
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/addTypeEquip", $this->data);
        $loadView->loadView();
    }
}
