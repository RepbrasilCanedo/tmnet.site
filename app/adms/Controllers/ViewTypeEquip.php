<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar Tipos de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewTypeEquip
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar tipo
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewTypEquip
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar Tipos.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewTypeEquip = new \App\adms\Models\AdmsViewTypeEquip();
            $viewTypeEquip->viewTypeEquip($this->id);
            if ($viewTypeEquip->getResult()) {
                $this->data['viewTypeEquip'] = $viewTypeEquip->getResultBd();
                $this->viewTypeEquip();
            } else {
                $urlRedirect = URLADM . "list-type-equip/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cor não encontrada!</p>";
            $urlRedirect = URLADM . "list-type-equip/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewTypeEquip(): void
    {
        $button = ['list_type_equip' => ['menu_controller' => 'list-type-equip', 'menu_metodo' => 'index'],
        'edit_type_equip' => ['menu_controller' => 'edit-type-equip', 'menu_metodo' => 'index'],
        'delete_type_equip' => ['menu_controller' => 'delete-type-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-type-equip"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/viewTypeEquip", $this->data);
        $loadView->loadView();
    }
}
