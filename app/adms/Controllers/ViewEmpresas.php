<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewEmpresas
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewEmpresas = new \App\adms\Models\AdmsViewEmpresas();
            $viewEmpresas->viewEmpresas($this->id);
            
            if ($viewEmpresas->getResult()) {
                // Passa o array do cliente já na posição 0
                $this->data['viewEmpresas'] = $viewEmpresas->getResultBd()[0];
                // Passa a lista de contratos
                $this->data['viewContratos'] = $viewEmpresas->getResultContratos();
                
                $this->viewEmpresas();
            } else {
                $urlRedirect = URLADM . "list-empresas/index";
                header("Location: $urlRedirect");
                exit;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cliente não encontrado!</p>";
            $urlRedirect = URLADM . "list-empresas/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    private function viewEmpresas(): void
    {
        $button = [
            'list_empresas' => ['menu_controller' => 'list-empresas', 'menu_metodo' => 'index'],
            'edit_empresas' => ['menu_controller' => 'edit-empresas', 'menu_metodo' => 'index'],
            'delete_empresas' => ['menu_controller' => 'delete-empresas', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-empresas"; // Padronizei para manter o menu correto aberto
        
        $loadView = new \Core\ConfigView("adms/Views/empresas/viewEmpresas", $this->data);
        $loadView->loadView();
    }
}