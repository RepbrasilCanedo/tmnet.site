<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller cadastrar usuário final
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AddUsersFinal
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        // Filtra a entrada de dados do formulário
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT); 

        if(!empty($this->dataForm['SendAddUserFinal'])){
            unset($this->dataForm['SendAddUserFinal']);
            
            $createUser = new \App\adms\Models\AdmsAddUsersFinal();
            $createUser->create($this->dataForm);
            
            if($createUser->getResult()){
                // Redirecionamento direto após sucesso
                header("Location: " . URLADM . "list-users-final/index");
                return; 
            }
            
            // Se houver erro, mantém os dados no formulário para o usuário não perder o que digitou
            $this->data['form'] = $this->dataForm;
        }

        $this->viewAddUserFinal();
    }

    /**
     * Centraliza a montagem da View e busca de dados para selects
     */
    private function viewAddUserFinal(): void
    {
        // Instancia a Model uma única vez para otimizar
        $admsAddUser = new \App\adms\Models\AdmsAddUsersFinal();
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $listMenu = new \App\adms\Models\helper\AdmsMenu();

        // Permissões de botões
        $button = ['list_users_final' => ['menu_controller' => 'list-users-final', 'menu_metodo' => 'index']];
        $this->data['button'] = $listBotton->buttonPermission($button);

        // Dados para os campos Select (Empresas, Situações, etc)
        $this->data['select'] = $admsAddUser->listSelect();

        // Dados do Menu e Sidebar
        $this->data['menu'] = $listMenu->itemMenu(); 
        $this->data['sidebarActive'] = "list-users-final"; 
        
        // Carregamento da View
        $loadView = new \Core\ConfigView("adms/Views/users/addUsersFinal", $this->data);
        $loadView->loadView();
    }
}