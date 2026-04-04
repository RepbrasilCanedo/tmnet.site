<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar a configuração de email
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewConfEmails
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar a configuração de email
     * Recebe como parametro o ID que será usado para pesquisar as informações no banco de dados e instancia a MODELS AdmsViewConfEmails
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para o listar configuração de e-mail.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;

            $viewConfEmails = new \App\adms\Models\AdmsViewConfEmails();
            $viewConfEmails->viewConfEmails($this->id);
            if ($viewConfEmails->getResult()) {
                $this->data['viewConfEmails'] = $viewConfEmails->getResultBd();
                $this->viewConfEmails();
            } else {
                $urlRedirect = URLADM . "list-conf-emails/index";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $urlRedirect = URLADM . "list-conf-emails/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewConfEmails(): void
    {
        $button = ['list_conf_emails' => ['menu_controller' => 'list-conf-emails', 'menu_metodo' => 'index'],
        'edit_conf_emails' => ['menu_controller' => 'edit-conf-emails', 'menu_metodo' => 'index'],
        'edit_conf_emails_password' => ['menu_controller' => 'edit-conf-emails-password', 'menu_metodo' => 'index'],
        'delete_conf_emails' => ['menu_controller' => 'delete-conf-emails', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-conf-emails"; 
        $loadView = new \Core\ConfigView("adms/Views/confEmails/viewConfEmails", $this->data);
        $loadView->loadView();
    }
}
