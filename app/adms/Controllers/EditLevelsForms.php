<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar o nivel de acesso para o formulario novo usuario na pagina de login
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditLevelsForms
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Metodo editar o nivel de acesso para o formulario novo usuario na pagina de login
     * Receber os dados do formulario.
     * 
     * Se o usuario nao clicou no botao editar, instancia a MODELS para recuperar as informacoes no banco de dados, se encontrar instancia o metodo "viewLevelsForms". Se nao existir redireciona para o dashboard.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (empty($this->dataForm['SendEditLevelsForms'])) {
            $viewLevelsForm = new \App\adms\Models\AdmsEditLevelsForms();
            $viewLevelsForm->viewLevelsForms();
            if ($viewLevelsForm->getResult()) {
                $this->data['form'] = $viewLevelsForm->getResultBd();
                $this->viewEditLevelsForm();
            } else {
                $urlRedirect = URLADM . "dashboard/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editLevelsForm();
        }
    }

    /**
     * Instanciar a classe responsavel em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditLevelsForm(): void
    {
        $button = ['view_levels_forms' => ['menu_controller' => 'view-levels-forms', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsEditLevelsForms();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "view-levels-forms"; 
        $loadView = new \Core\ConfigView("adms/Views/levelsForms/editLevelsForms", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar nivel de acesso para o formulario novo usuario na pagina de login.
     * Se o usuario clicou no botao, instancia a MODELS responsavel em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente no banco de dados.
     * Se o usuario nao clicou no botao redireciona para pagina dashboard.
     *
     * @return void
     */
    private function editLevelsForm(): void
    {
        if (!empty($this->dataForm['SendEditLevelsForms'])) {
            unset($this->dataForm['SendEditLevelsForms']);
            $editLevelsForm = new \App\adms\Models\AdmsEditLevelsForms();
            $editLevelsForm->update($this->dataForm);
            if ($editLevelsForm->getResult()) {
                $urlRedirect = URLADM . "view-levels-forms/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditLevelsForm();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página de configuração não encontrada!</p>";
            $urlRedirect = URLADM . "dashboard/index";
            header("Location: $urlRedirect");
        }
    }
}
