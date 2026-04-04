<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class SorteioGrupos
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (empty($this->id)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $sorteio = new \App\adms\Models\AdmsSorteioGrupos();
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->data['form']['AdmsGerarSorteio'])) {
            unset($this->data['form']['AdmsGerarSorteio']);
            
            $this->data['form']['inscricoes_ids'] = $_POST['inscricoes_ids'] ?? [];
            $this->data['form']['adms_competicao_id'] = $this->id;

            $sorteio->gerarSorteio($this->data['form']);
            header("Location: " . URLADM . "sorteio-grupos/index/" . $this->id);
            exit;
        }

        $detalhes = $sorteio->obterDetalhesCompeticao($this->id);
        
        $this->data['status_inscricao'] = $detalhes['status_inscricao'];
        $this->data['sistema_disputa'] = $detalhes['sistema_disputa'];
        $this->data['tipo_competicao'] = $detalhes['tipo_competicao'];
        $this->data['tipo_genero'] = $detalhes['tipo_genero'] ?? 1; // NOVO CAMPO

        $this->data['atletas'] = $sorteio->listarAtletasRanking($this->id);
        $this->data['grupos_gerados'] = $sorteio->listarGruposGerados($this->id);
        $this->data['competicao_id'] = $this->id;
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/sorteioGrupos", $this->data);
        $loadView->loadView();
    }
}