<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Avisos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListAviso
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

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

        $listAviso = new \App\adms\Models\AdmsListAviso();
        $listAviso->listAviso($this->page);    
        
        if ($listAviso->getResult()) {
            $this->data['listAviso'] = $listAviso->getResultBd();
            $this->data['pagination'] = $listAviso->getResultPg();
        } else {
            $this->data['listAviso'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['edit_aviso' => ['menu_controller' => 'edit-aviso', 'menu_metodo' => 'index']];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-aviso";         
        $loadView = new \Core\ConfigView("adms/Views/avisos/listAviso", $this->data);
        $loadView->loadView();
    }
}
