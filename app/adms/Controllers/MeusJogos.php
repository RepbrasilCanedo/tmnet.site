<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class MeusJogos
{
    private array|string|null $data = [];

    public function index(): void
    {
        // Pega o ID do usuário (Árbitro) que está logado
        $arbitroId = (int)$_SESSION['user_id'];

        $meusJogos = new \App\adms\Models\AdmsMeusJogos();
        $meusJogos->listarJogos($arbitroId);
        
        $this->data['jogos'] = $meusJogos->getResult();
        $this->data['sidebarActive'] = "meus-jogos";
        
        // Carrega o menu lateral padrão do sistema
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();
        
        $loadView = new \Core\ConfigView("adms/Views/partida/meusJogos", $this->data);
        $loadView->loadView();
    }
}