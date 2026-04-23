<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditPartida
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        // ========================================================================
        // DOCAN ENGINE: O RECETOR DO LIVE SCORE (AJAX) - Lendo $_POST direto!
        // ========================================================================
        if (isset($_POST['AjaxSyncLive'])) {
            $editPartida = new \App\adms\Models\AdmsEditPartida();
            $editPartida->syncLiveScore($_POST); // Passa o POST inteiro
            
            header('Content-Type: application/json');
            echo json_encode(['status' => true]);
            exit;
        }

        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->id = (int) $id;

        if (empty($this->id) && empty($this->data['form']['id'])) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Partida não encontrada!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $editPartida = new \App\adms\Models\AdmsEditPartida();

        $isSubmit = isset($this->data['form']['AdmsEditPartida']);

        if ($isSubmit) {
            unset($this->data['form']['AdmsEditPartida']);
            $editPartida->update($this->data['form']);

            if ($editPartida->getResult()) {
                $compId = $editPartida->getCompeticaoId();
                if ($_SESSION['adms_access_level_id'] == 15) {
                    header("Location: " . URLADM . "meus-jogos/index/{$compId}");
                } else {
                    header("Location: " . URLADM . "view-competicao/index/{$compId}");
                }
                exit;
            }
        } 
        
        if (!$isSubmit) {
            $editPartida->getPartida($this->id);
            if ($editPartida->getResultBd()) {
                $this->data['form'] = $editPartida->getResultBd()[0];
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Partida não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
                exit;
            }
        }

        $this->data['atletas'] = $editPartida->listAtletas();
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/partida/editPartida", $this->data);
        $loadView->loadView();
    }
}