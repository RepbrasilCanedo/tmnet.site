<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller SyncPagesLevels
 * @author Daniel Canedo - docan2006@gmail.com
 */
class SyncPagesLevels
{

    /** 
     * Metodo SyncPagesLevels
     * Instanciar a classe responsavel em sincronizar o nivel de acesso e as paginas
     * 
     * @return void
     */
    public function index(): void
    {

        $syncPagesLevels = new \App\adms\Models\AdmsSyncPagesLevels();
        $syncPagesLevels->syncPagesLevels();

        $urlRedirect = URLADM . "list-access-levels/index";
        header("Location: $urlRedirect");
    }
}
