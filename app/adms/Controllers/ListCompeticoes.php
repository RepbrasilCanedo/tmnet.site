<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ListCompeticoes
{
    private array|string|null $data;

    public function index(): void
    {
        // Recebe os dados do formulário de pesquisa
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $listComp = new \App\adms\Models\AdmsListCompeticoes();
        
        // Passa o array de busca para a Model
        $listComp->list($this->data['form']);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['listComp'] = $listComp->getResult();
        $this->data['sidebarActive'] = "list-competicoes";

        $loadView = new \Core\ConfigView("adms/Views/competicao/listCompeticao", $this->data);
        $loadView->loadView();
    }
}