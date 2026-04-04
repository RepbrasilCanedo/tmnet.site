<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerarAgenda
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

        $agenda = new \App\adms\Models\AdmsGerarAgenda();
        $this->data['form'] = $_POST;

        // Se clicou em Gerar Agenda
        if (!empty($this->data['form']['AdmsGerar'])) {
            $this->data['form']['adms_competicao_id'] = $this->id;
            $agenda->gerarJogos($this->data['form']);
            header("Location: " . URLADM . "gerar-agenda/index/" . $this->id);
            exit;
        }

        // Se clicou em Atribuir Árbitro a uma Mesa
        if (!empty($this->data['form']['AdmsAtribuirArbitro'])) {
            $agenda->atribuirArbitro($this->id, $this->data['form']);
            header("Location: " . URLADM . "gerar-agenda/index/" . $this->id);
            exit;
        }

        $this->data['agenda_jogos'] = $agenda->listarAgenda($this->id);
        $this->data['arbitros'] = $agenda->listarArbitros(); // LISTA DE ÁRBITROS PARA O SELECT
        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/gerarAgenda", $this->data);
        $loadView->loadView();
    }
}