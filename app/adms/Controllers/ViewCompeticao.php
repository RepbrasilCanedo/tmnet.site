<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewCompeticao
{
    private array|string|null $data;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $viewComp = new \App\adms\Models\AdmsViewCompeticao();
            
            $formData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            
            // DOCAN FIX: Interceta o clique do botão "Processar Ranking"
            if (!empty($formData['ProcessarRanking'])) {
                $viewComp->processarRankingOficial($this->id);
                // Redireciona para limpar o POST
                header("Location: " . URLADM . "view-competicao/index/{$this->id}");
                exit;
            }

            // DOCAN FIX: Interceta a mudança manual de Inscrições Abertas (1) para Andamento (2)
            if (!empty($formData['MudarStatusInscricao'])) {
                $statusAtual = (int)$formData['status_atual'];
                $novoStatus = ($statusAtual == 1) ? 2 : 1; 
                $viewComp->mudarStatusInscricao($this->id, $novoStatus);
                header("Location: " . URLADM . "view-competicao/index/{$this->id}");
                exit;
            }

            $viewComp->viewCompeticao($this->id);
            $this->data['viewComp'] = $viewComp->getResult();

            if ($this->data['viewComp']['detalhes']) {
                $listMenu = new \App\adms\Models\helper\AdmsMenu();
                $this->data['menu'] = $listMenu->itemMenu();
                $this->data['sidebarActive'] = "list-competicoes";

                $loadView = new \Core\ConfigView("adms/Views/competicao/viewCompeticao", $this->data);
                $loadView->loadView();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
            }
        } else {
            header("Location: " . URLADM . "list-competicoes/index");
        }
    }
}