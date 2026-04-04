<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Setores da empresa
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListSetor
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchSetor Recebe o setor da empresa*/
    private string|null $searchSetor;

    /**
     * Método listar setores da empresa.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->searchSetor = filter_input(INPUT_GET, 'searchSetor', FILTER_DEFAULT);

        $listsetor= new \App\adms\Models\AdmsListSetor();
        if (!empty($this->dataForm['SendSearchSetor'])) {
            $this->page = 1;
            $listsetor->listSearchSetor($this->page, $this->dataForm['search_nome']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchSetor))) {
            $listsetor->listSearchSetor($this->page, $this->searchSetor);
            $this->data['form']['search_nome'] = $this->searchSetor;
        } else {            
            $listsetor->listsetor($this->page);            
        }
        
        if ($listsetor->getResult()) {
            $this->data['listsetor'] = $listsetor->getResultBd();
            $this->data['pagination'] = $listsetor->getResultPg();
        } else {
            $this->data['listsetor'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_setor' => ['menu_controller' => 'add-setor', 'menu_metodo' => 'index'],
        'view_setor' => ['menu_controller' => 'view-setor', 'menu_metodo' => 'index'],
        'edit_setor' => ['menu_controller' => 'edit-setor', 'menu_metodo' => 'index'],
        'delete_setor' => ['menu_controller' => 'delete-setor', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-setor";         
        $loadView = new \Core\ConfigView("adms/Views/empresas/listSetor", $this->data);
        $loadView->loadView();
    }
}
