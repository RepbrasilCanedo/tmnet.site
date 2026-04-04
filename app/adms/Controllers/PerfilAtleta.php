<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class PerfilAtleta
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        // =====================================================================
        // TRAVA DE PRIVACIDADE ABSOLUTA
        // =====================================================================
        // Se o usuário logado for um Atleta (Nível 14), ele NUNCA pode ver outro ID.
        // Forçamos o ID da sessão, ignorando o que foi digitado na URL.
        if (isset($_SESSION['adms_niveis_acesso_id']) && $_SESSION['adms_niveis_acesso_id'] == 14) {
            $this->id = (int)$_SESSION['user_id'];
        } else {
            // Se for Administrador (Nível 1) ou Árbitro (Nível 15), ele pode pesquisar por ID
            $this->id = (int)$id;
        }

        if (empty($this->id)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não encontrado!</p>";
            header("Location: " . URLADM . "ranking/index");
            exit;
        }

        $perfil = new \App\adms\Models\AdmsPerfilAtleta();
        $perfil->carregarPerfil($this->id);

        if (!$perfil->getDadosPerfil()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não encontrado ou não pertence ao seu clube.</p>";
            header("Location: " . URLADM . "ranking/index");
            exit;
        }

        $this->data['perfil'] = $perfil->getDadosPerfil();
        $this->data['historico'] = $perfil->getHistorico();
        $this->data['estatisticas'] = $perfil->getEstatisticas();
        $this->data['proximos_jogos'] = $perfil->getProximosJogos();
        
        $this->data['sidebarActive'] = "perfil-atleta";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/ranking/perfilAtleta", $this->data);
        $loadView->loadView();
    }
}