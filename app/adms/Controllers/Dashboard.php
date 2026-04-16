<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class Dashboard
{
    private array|string|null $data;

    public function index(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $nivelAcesso = isset($_SESSION['adms_access_level_id']) ? (int)$_SESSION['adms_access_level_id'] : 0;
        $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
        
        $this->data['nivelAcesso'] = $nivelAcesso; 

        if ($nivelAcesso === 14) {
            // ==========================================================
            // VISÃO DO ATLETA (Vitrine de Competições)
            // ==========================================================
            $this->data['sidebarActive'] = "dashboard"; 
            
            $vitrine = new \App\adms\Models\AdmsDashboard();
            // DOCAN FIX: Passando o ID do Atleta para a Model procurar as inscrições dele
            $vitrine->getVitrineCompeticoes($userId); 
            
            $this->data['vitrine'] = $vitrine->getResult()['vitrine'] ?? [];
            
            $loadView = new \Core\ConfigView("adms/Views/dashboard/dashboard", $this->data);
            $loadView->loadView();

        } elseif ($nivelAcesso === 15) {
            // VISÃO DO ÁRBITRO
            $this->data['sidebarActive'] = "dashboard";

            $estatisticas = new \App\adms\Models\AdmsDashboard();
            $estatisticas->getEstatisticasArbitro($userId);
            $this->data['stats'] = $estatisticas->getResult();

            $loadView = new \Core\ConfigView("adms/Views/dashboard/dashboard", $this->data);
            $loadView->loadView();

        } else {
            // VISÃO DE ADMIN/ORGANIZAÇÃO
            $this->data['sidebarActive'] = "dashboard";

            $estatisticas = new \App\adms\Models\AdmsDashboard();
            $estatisticas->getEstatisticas();
            $this->data['stats'] = $estatisticas->getResult();

            $loadView = new \Core\ConfigView("adms/Views/dashboard/dashboard", $this->data);
            $loadView->loadView();
        }
    }
}