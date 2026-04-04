<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerenciarInscricoes
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

        $gerenciar = new \App\adms\Models\AdmsGerenciarInscricoes();
        $this->data['form'] = $_POST;

        if (!empty($this->data['form']['AdmsAddAtleta'])) {
            $this->data['form']['adms_competicao_id'] = $this->id;
            $gerenciar->inscreverAtletaManual($this->data['form']);
            
            header("Location: " . URLADM . "gerenciar-inscricoes/index/{$this->id}");
            exit;
        }

        $idRemover = filter_input(INPUT_GET, 'remover', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($idRemover)) {
            $gerenciar->removerInscricaoManual((int)$idRemover);
            header("Location: " . URLADM . "gerenciar-inscricoes/index/{$this->id}");
            exit;
        }

        $gerenciar->carregarListas($this->id);
        
        $this->data['inscritos'] = $gerenciar->getInscritos();
        $this->data['disponiveis'] = $gerenciar->getDisponiveis();
        $this->data['categorias_torneio'] = $gerenciar->getCategoriasTorneio();
        
        $readComp = new \App\adms\Models\helper\AdmsRead();
        $readComp->fullRead("SELECT data_evento FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$this->id}");
        $this->data['data_evento'] = $readComp->getResult()[0]['data_evento'] ?? date('Y-m-d');
        
        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/gerenciarInscricoes", $this->data);
        $loadView->loadView();
    }
}