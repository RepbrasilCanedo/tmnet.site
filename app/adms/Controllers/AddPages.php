<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar páginas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddPages
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Método cadastrar páginas
     * Receber os dados do formulário.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página cadastrar página. Acessa o IF e instância a classe "AdmsAddPages" responsável em cadastrar a página no banco de dados.
     * Página cadastrada com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);        

        if(!empty($this->dataForm['SendAddPages'])){
            //var_dump($this->dataForm);
            unset($this->dataForm['SendAddPages']);
            $createPages = new \App\adms\Models\AdmsAddPages();
            $createPages->create($this->dataForm);
            if($createPages->getResult()){
                $urlRedirect = URLADM . "list-pages/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddPages();
            }   
        }else{
            $this->viewAddPages();
        }  
    }

    /**
     * Instanciar a MODELS e o método "listSelect" responsável em buscar os dados para preencher o campo SELECT 
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddPages(): void
    {
        $button = ['list_pages' => ['menu_controller' => 'list-pages', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddPages();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-pages"; 
        
        $loadView = new \Core\ConfigView("adms/Views/pages/addPages", $this->data);
        $loadView->loadView();
    }
}
