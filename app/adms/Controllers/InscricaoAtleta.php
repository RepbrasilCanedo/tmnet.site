<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class InscricaoAtleta
{
    private array|string|null $data = [];

    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            header("Location: " . URLADM . "login/index");
            exit;
        }

        if ($_SESSION['adms_access_level_id'] == 4 || $_SESSION['adms_access_level_id'] == 1) {
            $_SESSION['msg'] = "<p class='alert-warning'>Área restrita para atletas. Administradores devem usar o painel da Súmula para gerir inscrições.</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $this->data['form'] = $_POST;
        $userId = $_SESSION['user_id'];

        $inscricaoModel = new \App\adms\Models\AdmsInscricaoAtleta();

        if (!empty($this->data['form']['AdmsInscrever']) || !empty($this->data['form']['AdmsAtualizar'])) {
            $inscricaoModel->inscrever($this->data['form']);
            header("Location: " . URLADM . "inscricao-atleta/index");
            exit;
        } elseif (!empty($this->data['form']['AdmsCancelar'])) {
            $inscricaoModel->cancelarInscricao($this->data['form']);
            header("Location: " . URLADM . "inscricao-atleta/index");
            exit;
        }

        // Carrega dados para a View
        $inscricaoModel->listarTorneios($userId);
        $this->data['torneios'] = $inscricaoModel->getResultBd();

        $this->data['sidebarActive'] = "inscricao-atleta";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/inscricaoAtleta", $this->data);
        $loadView->loadView();
    }
}