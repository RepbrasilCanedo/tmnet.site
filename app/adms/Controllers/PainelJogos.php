<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class PainelJogos
{
    private array|string|null $data = [];

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (!empty($id)) {
            $painel = new \App\adms\Models\AdmsPainelJogos();
            $painel->listarJogosPainel($id);
            
            // ========================================================================
            // DOCAN FIX: A PORTA DO AJAX (Se pedir só os dados, devolve só o JSON)
            // ========================================================================
            if (isset($_GET['ajax']) && $_GET['ajax'] == 'true') {
                header('Content-Type: application/json');
                echo json_encode($painel->getResult());
                exit; // Morre aqui para não desenhar o HTML de novo
            }

            $this->data['painel'] = $painel->getResult();
            $this->data['competicao_id'] = $id;

            $listMenu = new \App\adms\Models\helper\AdmsMenu();
            $this->data['menu'] = $listMenu->itemMenu();
            $this->data['sidebarActive'] = "list-competicoes";

            $loadView = new \Core\ConfigView("adms/Views/competicao/painelJogos", $this->data);
            $loadView->loadView();
        } else {
            header("Location: " . URLADM . "list-competicoes/index");
        }
    }
}