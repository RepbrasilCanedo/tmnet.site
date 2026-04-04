<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AvancarMataMata
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (empty($this->id)) {
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $avancar = new \App\adms\Models\AdmsAvancarMataMata();
        
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        if (!empty($this->data['form']['AdmsAvancarFase'])) {
            $avancar->avancarFase($this->id);
            header("Location: " . URLADM . "view-competicao/index/{$this->id}");
            exit;
        }

        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/avancarMataMata", $this->data);
        $loadView->loadView();
    }
}