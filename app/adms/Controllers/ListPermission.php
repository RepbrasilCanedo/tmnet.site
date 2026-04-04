<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar permissoes
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListPermission
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o numero pagina */
    private string|int|null $page;

    /** @var string|int|null $level Recebe o id do nivel de acesso */
    private string|int|null $level;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome da cor */
    private string|null $searchName;

    /**
     * Metodo listar permissoes.
     * 
     * Instancia a MODELS responsavel em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senao redireciona para a pagina listar nivel de acesso
     *
     * @return void
     */
    public function index(string|int|null $page = null)
    {
        $this->level = filter_input(INPUT_GET, 'level', FILTER_SANITIZE_NUMBER_INT);
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);

        $listPermission = new \App\adms\Models\AdmsListPermission();
        if (!empty($this->dataForm['SendSearchPermission'])) {
            $this->page = 1;
            $listPermission->listSearchPermission($this->page, $this->level, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        }elseif (!empty($this->searchName)) {
            $listPermission->listSearchPermission($this->page, $this->level, $this->searchName);
            $this->data['form']['search_name'] = $this->searchName;
        }else { 
            $listPermission->listPermission($this->page, $this->level);            
        }

        if ($listPermission->getResult()) {
            $this->data['listPermission'] = $listPermission->getResultBd();
            $this->data['viewAccessLevel'] = $listPermission->getResultBdLevel();
            $this->data['pagination'] = $listPermission->getResultPg();
            $this->data['pag'] = $this->page;
            $this->viewPermission();
        } else {
            //$this->data['listUsers'] = [];
            //$this->data['pagination'] = null;
            $urlRedirect = URLADM . "list-access-levels/index";
            header("Location: $urlRedirect");
        }        
    }

    /**
     * Instanciar a classe responsavel em carregar a View e enviar os dados para View.
     * 
     */
    private function viewPermission(): void
    {
        $button = ['list_access_levels' => ['menu_controller' => 'list-access-levels', 'menu_metodo' => 'index'],
        'order_page_menu' => ['menu_controller' => 'order-page-menu', 'menu_metodo' => 'index'],
        'edit_page_menu' => ['menu_controller' => 'edit-page-menu', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-access-levels";

        $loadView = new \Core\ConfigView("adms/Views/permission/listPermission", $this->data);
        $loadView->loadView();
    }
}
