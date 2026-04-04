<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da pagina visualizar o nivel de acesso para o formulario novo usuario na pagina de login
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewLevelsForms
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /**
     * Metodo visualizar o nivel de acesso para o formulario novo usuario na pagina de login.
     * Instancia a MODELS.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o dashboard.
     * @return void
     */
    public function index(): void
    {
            $viewLevelsForm = new \App\adms\Models\AdmsLevelsForms();
            $viewLevelsForm->viewLevelsForms();
            if ($viewLevelsForm->getResult()) {
                $this->data['viewLevelsForm'] = $viewLevelsForm->getResultBd();
                $this->viewLevelsForm();
            } else {
                //$_SESSION['msg'] = "<p class='alert-danger'>Erro: Página de configuração não encontrada!</p>";
                $urlRedirect = URLADM . "dashboard/index";
                header("Location: $urlRedirect");
            }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewLevelsForm(): void
    {
        $button = ['edit_levels_forms' => ['menu_controller' => 'edit-levels-forms', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['sidebarActive'] = "view-levels-forms";
        $loadView = new \Core\ConfigView("adms/Views/levelsForms/viewLevelsForms", $this->data);
        $loadView->loadView();
    }
}
