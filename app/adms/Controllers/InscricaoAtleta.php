<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class InscricaoAtleta
{
    private array|string|null $data = [];

    public function index(int|string|null $id = null): void
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . URLADM . "login/index");
            exit;
        }

        if ($_SESSION['adms_access_level_id'] <> 14) {
            $_SESSION['msg'] = "<p class='alert-warning'>Área exclusiva para Atletas.</p>";
            header("Location: " . URLADM . "dashboard/index");
            exit;
        }

        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $userId = $_SESSION['user_id'];
        $torneioId = !empty($id) ? (int)$id : null;

        $inscricaoModel = new \App\adms\Models\AdmsInscricaoAtleta();

        if (!empty($this->data['form']['AdmsInscrever']) || !empty($this->data['form']['AdmsAtualizar'])) {
            $inscricaoModel->inscrever($this->data['form']);
            $urlRedirect = $torneioId ? "inscricao-atleta/index/{$torneioId}" : "inscricao-atleta/index";
            header("Location: " . URLADM . $urlRedirect);
            exit;
        } elseif (!empty($this->data['form']['AdmsCancelar'])) {
            $inscricaoModel->cancelarInscricao($this->data['form']);
            $urlRedirect = $torneioId ? "inscricao-atleta/index/{$torneioId}" : "inscricao-atleta/index";
            header("Location: " . URLADM . $urlRedirect);
            exit;
        }

        $inscricaoModel->listarTorneios($userId, $torneioId);
        $this->data['torneios'] = $inscricaoModel->getResultBd();

        $this->data['sidebarActive'] = "inscricao-atleta";
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/inscricaoAtleta", $this->data);
        $loadView->loadView();
    }
}