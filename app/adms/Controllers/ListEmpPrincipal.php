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
class ListEmpPrincipal
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $sesearchCnpj Recebe o cnpj da empresa*/
    private string|null $searchCnpj;

    /** @var string|null $sesearchRazao Recebe a razao social da empresa*/
    private string|null $searchRazao;

    /** @var string|null $searchFantasia Recebe o nome de fantasia da empresaal */
    private string|null $searchFantasia;

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

        $this->searchCnpj = filter_input(INPUT_GET, 'searchCnpj', FILTER_DEFAULT);
        $this->searchRazao = filter_input(INPUT_GET, 'searchRazao', FILTER_DEFAULT);
        $this->searchFantasia= filter_input(INPUT_GET, 'searchFantasia', FILTER_DEFAULT);

        $listEmpresas= new \App\adms\Models\AdmsListEmpPrincipal();
        
        if (!empty($this->dataForm['SendSearchEmpPrincipal'])) {
            $this->page = 1;
            $listEmpresas->listSearchEmpPrincipal($this->page, $this->dataForm['search_cnpj'], $this->dataForm['search_razao'], $this->dataForm['search_fantasia']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchCnpj)) or (!empty($this->searchRazao)) or (!empty($this->searchFantasia))) {
            $listEmpresas->listSearchEmpPrincipal($this->page, $this->searchCnpj, $this->searchRazao, $this->searchFantasia);
            $this->data['form']['search_cnpj'] = $this->searchCnpj;
            $this->data['form']['search_razao'] = $this->searchRazao;
            $this->data['form']['search_fantasia'] = $this->searchFantasia;
        } else {            
            $listEmpresas->listEmpPrincipal($this->page);            
        }
        
        if ($listEmpresas->getResult()) {
            $this->data['listEmpPrincipal'] = $listEmpresas->getResultBd();
            $this->data['pagination'] = $listEmpresas->getResultPg();
        } else {
            $this->data['listEmpPrincipal'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_emp_principal' => ['menu_controller' =>'add-emp-principal', 'menu_metodo' => 'index'],
        'view_emp_principal' => ['menu_controller' => 'view-emp-principal', 'menu_metodo' => 'index'],
        'edit_emp_principal' => ['menu_controller' => 'edit-emp-principal', 'menu_metodo' => 'index'],
        'delete_emp_principal' => ['menu_controller' => 'delete-emp-principal', 'menu_metodo' => 'index']];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-emp-principal";         
        $loadView = new \Core\ConfigView("adms/Views/empresas/listEmpPrincipal", $this->data);
        $loadView->loadView();
    }
}
