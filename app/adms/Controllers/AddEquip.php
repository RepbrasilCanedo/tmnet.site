<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar Equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddEquip
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Método cadastrar equipamentos
     * Receber os dados do formulário.
     * Quando o usuário clicar no botão "cadastrar" do formulário da página cadastrar equipamentos. Acessa o IF e instância a classe "AdmsAddEquip" responsável em cadastrar a página no banco de dados.
     * Equipamento cadastrado com sucesso, redireciona para a página listar registros.
     * Senão, instância a classe responsável em carregar a View e enviar os dados para View.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);        

        if(!empty($this->dataForm['SendAddEquip'])){
            //var_dump($this->dataForm);
            unset($this->dataForm['SendAddEquip']);
            $createEquip = new \App\adms\Models\AdmsAddEquip();
            $createEquip->create($this->dataForm);
            
            if($createEquip->getResult()){
                $urlRedirect = URLADM . "list-equip/index";
                header("Location: $urlRedirect");
            }else{
                $this->data['form'] = $this->dataForm;
                $this->viewAddEquip();
            }   
        }else{
            $this->viewAddEquip();
        }  
    }

    /**
     * Instanciar a MODELS e o método "listSelect" responsável em buscar os dados para preencher o campo SELECT 
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewAddEquip(): void
    {
        $button = ['list_equip' => ['menu_controller' => 'list-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddEquip();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-equip"; 
        
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/addEquip", $this->data);
        $loadView->loadView();
    }
}
