<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar Sla dos Tickets
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewTicketSla
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar Sla dos Tickets
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewTicketSla
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar cores.
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewTicketSla = new \App\adms\Models\AdmsViewTicketSla();
            $viewTicketSla->viewTicketSla($this->id);
            if ($viewTicketSla->getResult()) {
                $this->data['viewTicketSla'] = $viewTicketSla->getResultBd();
                $this->viewTicketSla();
            } else {
                $urlRedirect = URLADM . "list-ticket-sla/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Sla dos Tickets não encontrada!</p>";
            $urlRedirect = URLADM . "list-colors/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewTicketSla(): void
    {
        $button = ['list_ticket_sla' => ['menu_controller' => 'list-ticket-sla', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-ticket-sla"; 
        $loadView = new \Core\ConfigView("adms/Views/sla/viewTicketSla", $this->data);
        $loadView->loadView();
    }
}
