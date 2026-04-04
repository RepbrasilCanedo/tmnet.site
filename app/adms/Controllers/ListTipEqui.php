<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar cores
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListTipEqui
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o nome da cor */
    private string|null $searchName;

    /** @var string|null $searchEmp Recebe o nome da cor em hexadecimal */
    private string|null $searchEmp;

    /**
     * Método listar cores.
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

            $this->searchName = filter_input(INPUT_GET, 'search_name', FILTER_DEFAULT);

        $listTipEqui = new \App\adms\Models\AdmsListTipEqui();

        if (!empty($this->dataForm['SendSearchTipEqui'])) {
            $this->page = 1;
            $listTipEqui->listSearcTipEqui($this->page, $this->dataForm['search_name']);
            $this->data['form'] = $this->dataForm;
        } else {            
            $listTipEqui->listTipEqui($this->page);            
        }
        
        if ($listTipEqui->getResult()) {
            $this->data['listTipEqui'] = $listTipEqui->getResultBd();
            $this->data['pagination'] = $listTipEqui->getResultPg();
        } else {
            $this->data['listTipEqui'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_tip_equi' => ['menu_controller' => 'add-tip-equi', 'menu_metodo' => 'index'],
        'view_tip_equi' => ['menu_controller' => 'view-tip-equi', 'menu_metodo' => 'index'],
        'edit_tip_equi' => ['menu_controller' => 'edit-tip-equi', 'menu_metodo' => 'index'],
        'delete_tip_equi' => ['menu_controller' => 'delete-tip-equi', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-tip-equi";         
        $loadView = new \Core\ConfigView("adms/Views/produtos/listTipEqui", $this->data);
        $loadView->loadView();
    }
}
