<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerarMataMata
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

        $mataMata = new \App\adms\Models\AdmsGerarMataMata();
        
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->data['form']['AdmsGerarMataMata'])) {
            
            // Agora a Model gera as chaves e a Agenda cuida das mesas!
            $mataMata->gerarChaves($this->id);
            
            header("Location: " . URLADM . "view-competicao/index/{$this->id}");
            exit;
        }

        $this->data['classificacao'] = $mataMata->getClassificacaoGrupos($this->id);
        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/gerarMataMata", $this->data);
        $loadView->loadView();
    }
}