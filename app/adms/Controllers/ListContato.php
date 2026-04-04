<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die();
}

/**
 * Controller listar mensagens de contato
 */
class ListContato // Verifique se aqui está escrito exatamente ListContato
{
    private array|string|null $data;
    private string|int|null $page;

    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;
        
        $listContato = new \App\adms\Models\AdmsListContato();
        $listContato->listContato($this->page);    
        
        if ($listContato->getResult()) {
            $this->data['listContato'] = $listContato->getResultBd();
            $this->data['pagination'] = $listContato->getResultPg();
        } else {
            $this->data['listContato'] = [];
            $this->data['pagination'] = "";
        }

        // Permissões de botões
        $button = [
            'view_contato' => ['menu_controller' => 'view-contato', 'menu_metodo' => 'index'],
            'edit_contato' => ['menu_controller' => 'edit-contato', 'menu_metodo' => 'index'], 
            'delete_mensagem' => ['menu_controller' => 'delete-mensagem', 'menu_metodo' => 'index']
        ];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu(); 
        $this->data['sidebarActive'] = "list-contato";         
        
        $loadView = new \Core\ConfigView("adms/Views/contato/listContato", $this->data);
        $loadView->loadView();
    }
}