<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AddCompeticoes
{
    private array|string|null $data = null;

    public function index(): void
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->data['AdmsAddComp'])) {
            unset($this->data['AdmsAddComp']);
            
            $addComp = new \App\adms\Models\AdmsAddCompeticao();
            $addComp->create($this->data);

            if ($addComp->getResult()) {
                $urlRedirect = URLADM . "list-competicoes/index";
                header("Location: $urlRedirect");
            } else {
                $this->viewAddComp();
            }
        } else {
            $this->viewAddComp();
        }
    }

    private function viewAddComp(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        // Puxa as categorias do clube para mostrar as checkboxes
        $addComp = new \App\adms\Models\AdmsAddCompeticao();
        $this->data['categorias_clube'] = $addComp->getCategoriasClube();

        $this->data['sidebarActive'] = "list-competicoes"; 
        $loadView = new \Core\ConfigView("adms/Views/competicao/addCompeticao", $this->data);
        $loadView->loadView();
    }
}