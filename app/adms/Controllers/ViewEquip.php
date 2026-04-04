<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar detalhes da página
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewEquip
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar detalhe da página
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewPages
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar páginas
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewEquip = new \App\adms\Models\AdmsViewEquip();
            $viewEquip->viewEquip($this->id);
            if ($viewEquip->getResult()) {
                $this->data['viewEquip'] = $viewEquip->getResultBd();
                $this->viewEquip();
            } else {
                $urlRedirect = URLADM . "list-equip/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não encontrado!</p>";
            $urlRedirect = URLADM . "list-equip/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEquip(): void
    {
        $button = ['list_equip' => ['menu_controller' => 'list-equip', 'menu_metodo' => 'index'],
        'edit_equip' => ['menu_controller' => 'edit-equip', 'menu_metodo' => 'index'],
        'delete_equip' => ['menu_controller' => 'delete-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-equip";
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/viewEquip", $this->data);
        $loadView->loadView();
    }
}
