<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class Ranking
{
    private array|string|null $data;

    public function index(): void
    {
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $ranking = new \App\adms\Models\AdmsRankingGeral();
        $ranking->listarRanking($this->data['form']);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        // Pega os dados processados na Model
        $dadosModel = $ranking->getResult();
        $this->data['ranking_geral'] = $dadosModel['geral'] ?? [];
        
        // CORREÇÃO AQUI: Passando as Categorias para a View em vez de Divisões
        $this->data['ranking_categoria'] = $dadosModel['por_categoria'] ?? [];
        
        $this->data['sidebarActive'] = "ranking";

        $loadView = new \Core\ConfigView("adms/Views/ranking/rankingGeral", $this->data);
        $loadView->loadView();
    }
}