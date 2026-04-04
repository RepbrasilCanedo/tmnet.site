<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller sair do administrativo.
 * @author Daniel Canedo - docan2006@gmail.com
 */
class Logout
{
    /**
     * Método sair do administrativo.
     * Atualiza o status para offline e destrói as sessões.
     * * @return void
     */
    public function index(): void
    {
        // 1. Antes de deslogar, limpamos a atividade no banco de dados
        if (isset($_SESSION['user_id'])) {
            $updateStatus = new \App\adms\Models\helper\AdmsUpdate();
            // Definimos uma data bem antiga para garantir que o cálculo do INTERVAL o considere offline
            $updateStatus->exeUpdate(
                "adms_users", 
                ["last_activity" => "2000-01-01 00:00:00"], 
                "WHERE id = :id", 
                "id={$_SESSION['user_id']}"
            );
        }

        // 2. Destruir as sessões do usuário logado
        unset(
            $_SESSION['user_id'], 
            $_SESSION['user_name'], 
            $_SESSION['user_nickname'], 
            $_SESSION['user_email'], 
            $_SESSION['user_image'], 
            $_SESSION['emp_user'], 
            $_SESSION['set_Contr'], 
            $_SESSION['adms_access_level_id'],
            $_SESSION['search_sla_filter'] // Limpa também filtros de relatórios se existirem
        );

        $_SESSION['msg'] = "<p class='alert-success'>Logout realizado com sucesso!</p>";
        
        $urlRedirect = URLADM . "login/index";
        header("Location: $urlRedirect");
    }
}