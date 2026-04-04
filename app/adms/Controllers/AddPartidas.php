<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AddPartidas
{
    private array|string|null $data = null;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (empty($this->id)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->data['AdmsAddPartida'])) {
            unset($this->data['AdmsAddPartida']);
            
            $addPartida = new \App\adms\Models\AdmsAddPartida();
            $addPartida->create($this->data);

            // Redireciona para evitar reenvio de formulário ao atualizar F5
            header("Location: " . URLADM . "add-partidas/index/{$this->id}");
            exit;
        } else {
            $this->viewAddPartida();
        }
    }

    private function viewAddPartida(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $addPartida = new \App\adms\Models\AdmsAddPartida();
        $this->data['atletas'] = $addPartida->listAtletas();
        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";

        $loadView = new \Core\ConfigView("adms/Views/partida/addPartida", $this->data);
        $loadView->loadView();
    }
}