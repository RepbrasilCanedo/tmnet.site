<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ListCategorias
{
    private array|string|null $data;

    public function index(): void
    {
        $listCat = new \App\adms\Models\AdmsListCategorias();
        $listCat->listCategorias();
        $this->data['listCat'] = $listCat->getResult();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        $this->data['sidebarActive'] = "list-categorias"; 
        
        $loadView = new \Core\ConfigView("adms/Views/categoria/listCategorias", $this->data);
        $loadView->loadView();
    }
}