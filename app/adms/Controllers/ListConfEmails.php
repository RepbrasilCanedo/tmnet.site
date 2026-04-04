<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar configuração de emails
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListConfEmails
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchName Recebe o titulo */
    private string|null $searchTitle;

    /** @var string|null $searchColor Recebe o e-mail */
    private string|null $searchEmail;

    /**
     * Método listar configuração de emails.
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

        $this->searchTitle = filter_input(INPUT_GET, 'search_title', FILTER_DEFAULT);
        $this->searchEmail = filter_input(INPUT_GET, 'search_email', FILTER_DEFAULT);

        $listConfEmails = new \App\adms\Models\AdmsListConfEmails();
        if (!empty($this->dataForm['SendSearchConfemail'])) {
            $this->page = 1;
            $listConfEmails->listSearchConfEmails($this->page, $this->dataForm['search_title'], $this->dataForm['search_email']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchTitle)) or (!empty($this->searchEmail))) {
            $listConfEmails->listSearchConfEmails($this->page, $this->searchTitle, $this->searchEmail);
            $this->data['form']['search_title'] = $this->searchTitle;
            $this->data['form']['search_email'] = $this->searchEmail;
        } else {            
            $listConfEmails->listConfEmails($this->page);            
        }
        
        if($listConfEmails->getResult()){
            $this->data['listConfEmails'] = $listConfEmails->getResultBd(); 
            $this->data['pagination'] = $listConfEmails->getResultPg();
        }else{
            $this->data['listConfEmails'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_conf_emails' => ['menu_controller' => 'add-conf-emails', 'menu_metodo' => 'index'],
        'view_conf_emails' => ['menu_controller' => 'view-conf-emails', 'menu_metodo' => 'index'],
        'edit_conf_emails' => ['menu_controller' => 'edit-conf-emails', 'menu_metodo' => 'index'],
        'delete_conf_emails' => ['menu_controller' => 'delete-conf-emails', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-conf-emails"; 
        $loadView = new \Core\ConfigView("adms/Views/confEmails/listConfEmails", $this->data);
        $loadView->loadView();
    }
}
