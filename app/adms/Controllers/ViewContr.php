<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar detalhes do contrato
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewContr
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar detalhe do contrato
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsContr
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar contratos
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewContr = new \App\adms\Models\AdmsViewContr();
            $viewContr->viewContr($this->id);
            
            if ($viewContr->getResult()) {
                $this->data['viewContr'] = $viewContr->getResultBd();
                $this->viewContr();
            } else {
                $urlRedirect = URLADM . "list-contr/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado!</p>";
            $urlRedirect = URLADM . "list-contr/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewContr(): void
    {
        $button = ['list_contr' => ['menu_controller' => 'list-contr', 'menu_metodo' => 'index'],
        'edit_contr' => ['menu_controller' => 'edit-contr', 'menu_metodo' => 'index'],
        'delete_contr' => ['menu_controller' => 'delete-contr', 'menu_metodo' => 'index']];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contr";
        $loadView = new \Core\ConfigView("adms/Views/contratos/viewContr", $this->data);
        $loadView->loadView();
    }
}
