<?php

namespace Core;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
/**
 * Verificar se o nivel de acesso do usuario tem permissao de acessa a pagina.
 * Carregar a CONTROLLER
 * @author Daniel Canedo - docan2006@gmail.com
 */
class CarregarPgAdmLevel
{
    private string $urlController;
    private string $urlMetodo;
    private string $urlParameter;
    private string $classLoad;
    private array|null $resultPage;
    private array|null $resultLevelPage;

    public function loadPage(string|null $urlController, string|null $urlMetodo, string|null $urlParameter): void
    {
        $this->urlController = $urlController;
        $this->urlMetodo = $urlMetodo;
        $this->urlParameter = $urlParameter;
        $this->searchPage();
    }

    private function searchPage(): void
    {
        $searchPage = new \App\adms\Models\helper\AdmsRead();
        $searchPage->fullRead("SELECT pag.id, pag.publish, typ.type
                    FROM adms_pages AS pag
                    INNER JOIN adms_types_pgs AS typ ON typ.id=pag.adms_types_pgs_id 
                    WHERE pag.controller =:controller 
                    AND pag.metodo =:metodo
                    AND pag.adms_sits_pgs_id =:adms_sits_pgs_id
                    LIMIT :limit", "controller={$this->urlController}&metodo={$this->urlMetodo}&adms_sits_pgs_id=1&limit=1");
        $this->resultPage = $searchPage->getResult();
        if ($this->resultPage) {
            if ($this->resultPage[0]['publish'] == 1) {
                $this->classLoad = "\\App\\" . $this->resultPage[0]['type'] . "\\Controllers\\" . $this->urlController;
                $this->loadMetodo();
            } else {
                $this->verifyLogin();
            }
        } else {
            die("Erro - 006: Por favor tente novamente. Caso o problema persista, entre em contato com o administrador " . EMAILADM);
        }
    }

    private function loadMetodo(): void
    {
        $classLoad = new $this->classLoad();
        if (method_exists($classLoad, $this->urlMetodo)) {
            $classLoad->{$this->urlMetodo}($this->urlParameter);
        } else {
            die("Erro - 007: Por favor tente novamente. Caso o problema persista, entre em contato com o administrador " . EMAILADM);
        }
    }

    private function verifyLogin(): void
    {
        if ((isset($_SESSION['user_id'])) and (isset($_SESSION['user_name']))  and (isset($_SESSION['user_email'])) and (isset($_SESSION['adms_access_level_id'])) and (isset($_SESSION['order_levels']))) {
            $this->searchLevelPage();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Para acessar a página realize o login!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
            exit; // DOCAN FIX: Trava a execução do script para não destruir a sessão!
        }
    }

    private function searchLevelPage(): void
    {
        $searchLevelPage = new \App\adms\Models\helper\AdmsRead();
        $searchLevelPage->fullRead("SELECT id, permission 
                    FROM adms_levels_pages
                    WHERE adms_page_id =:adms_page_id 
                    AND adms_access_level_id =:adms_access_level_id 
                    AND permission =:permission
                    LIMIT :limit", "adms_page_id={$this->resultPage[0]['id']}&adms_access_level_id=" . $_SESSION['adms_access_level_id'] . "&permission=1&limit=1");
        $this->resultLevelPage = $searchLevelPage->getResult();

        if ($this->resultLevelPage) {
            $this->classLoad = "\\App\\" . $this->resultPage[0]['type'] . "\\Controllers\\" . $this->urlController;
            $this->loadMetodo();
        } else {            
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
            exit; // DOCAN FIX: Trava a execução do script!
        }
    }
}