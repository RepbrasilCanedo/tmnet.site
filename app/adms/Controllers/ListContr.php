<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Contratos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListContr
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchId;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchType;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchServ;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchEmp;

    /**
     * Método listar páginas.
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


        $listContr = new \App\adms\Models\AdmsListContr();
        $listContr->listContr($this->page);

        if ($listContr->getResult()) {
            $this->data['listContr'] = $listContr->getResultBd();
            $this->data['pagination'] = $listContr->getResultPg();
        } else {
            $this->data['listContr'] = [];
            $this->data['pagination'] = "";
        }


        $button = [
            'add_contr' => ['menu_controller' => 'add-contr', 'menu_metodo' => 'index'],
            'view_contr' => ['menu_controller' => 'view-contr', 'menu_metodo' => 'index'],
            'edit_contr' => ['menu_controller' => 'edit-contr', 'menu_metodo' => 'index'],
            'delete_contr' => ['menu_controller' => 'delete-contr', 'menu_metodo' => 'index']
        ];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsListContr();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['pag'] = $this->page;

        $this->data['sidebarActive'] = "list-contr";
        $loadView = new \Core\ConfigView("adms/Views/contratos/listContr", $this->data);
        $loadView->loadView();
    }
}
