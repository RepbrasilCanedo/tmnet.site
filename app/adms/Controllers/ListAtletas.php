<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ListAtletas
{
    private array|string|null $data;

    public function index(): void
    {
        $listAtletas = new \App\adms\Models\AdmsListAtletas();
        $listAtletas->listAtletas();
        
        $this->data['listAtletas'] = $listAtletas->getResult();
        $this->data['sidebarActive'] = "list-atletas";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $loadView = new \Core\ConfigView("adms/Views/atleta/listAtleta", $this->data);
        $loadView->loadView();
    }
}