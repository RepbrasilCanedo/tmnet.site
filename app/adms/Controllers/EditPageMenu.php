<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da pagina editar pagina no item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditPageMenu
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var int|string|null $level Recebe o nivel de acesso */
    private int|string|null $level;

    /** @var int|string|null $pag Recebe o numero da pagina */
    private int|string|null $pag;

    /**
     * Metodo alterar ordem do item de menu 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;
        $this->level = filter_input(INPUT_GET, "level", FILTER_SANITIZE_NUMBER_INT);
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($this->id)) and (!empty($this->level)) and (!empty($this->pag)) and (empty($this->dataForm['SendEditPageMenu']))) {
            $viewPageMenu = new \App\adms\Models\AdmsEditPageMenu();
            $viewPageMenu->viewPageMenu($this->id);

            if ($viewPageMenu->getResult()) {
                $this->data['form']= $viewPageMenu->getResultBd();
                $this->viewEditPageMenu();
            } else {
                $urlRedirect = URLADM . "list-permission/index/{$this->pag}?level={$this->level}";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editPageMenu();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditPageMenu(): void
    {
        $button = ['list_permission' => ['menu_controller' => 'list-permission', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $this->data['btnLevel'] = $this->level;

        $listSelect = new \App\adms\Models\AdmsEditPageMenu();
        $this->data['select'] = $listSelect->listSelect();
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-access-levels"; 
        $loadView = new \Core\ConfigView("adms/Views/permission/editPageMenu", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar pagina item de menu.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a pagina item de menu no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar nivel de acesso.
     *
     * @return void
     */
    private function editPageMenu(): void
    {
        if (!empty($this->dataForm['SendEditPageMenu'])) {
            unset($this->dataForm['SendEditPageMenu']);
            $editPageMenu = new \App\adms\Models\AdmsEditPageMenu();
            $editPageMenu->update($this->dataForm);
            if ($editPageMenu->getResult()) {
                $urlRedirect = URLADM . "list-permission/index/{$this->pag}?level={$this->level}";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditPageMenu();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu da página não encontrado!</p>";
            $urlRedirect = URLADM . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }
}
