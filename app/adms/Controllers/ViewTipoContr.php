<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller visualizar tipo de contrato
 */
class ViewTipoContr
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $viewTipoContr = new \App\adms\Models\AdmsViewTipoContr();
            $viewTipoContr->viewTipoContr($this->id);
            
            if ($viewTipoContr->getResult()) {
                // Pega a posição 0 do array, pois a query com LIMIT 1 retorna dentro de um array
                $this->data['viewTipoContr'] = $viewTipoContr->getResultBd()[0];
                $this->viewInfoTipoContr();
            } else {
                $urlRedirect = URLADM . "list-tipo-contr/index";
                header("Location: $urlRedirect");
                exit;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de Contrato não encontrado!</p>";
            $urlRedirect = URLADM . "list-tipo-contr/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    private function viewInfoTipoContr(): void
    {
        // Botões de ação permitidos na tela de visualização
        $button = [
            'list_tipo_contr' => ['menu_controller' => 'list-tipo-contr', 'menu_metodo' => 'index'],
            'edit_tipo_contr' => ['menu_controller' => 'edit-tipo-contr', 'menu_metodo' => 'index'],
            'delete_tipo_contr' => ['menu_controller' => 'delete-tipo-contr', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-tipo-contr"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/viewTipoContr", $this->data);
        $loadView->loadView();
    }
}