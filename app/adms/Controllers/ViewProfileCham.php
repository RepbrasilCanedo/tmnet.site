<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar perfil
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewProfileCham
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Metodo visualizar perfil
     * Instancia a MODELS AdmsViewProfile para pesquisar as informações do usuário
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão é redirecionado para a página de login.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($id)) {
            $this->id = (int) $id;
            $viewProfileCham = new \App\adms\Models\AdmsViewProfileCham();
            $viewProfileCham->viewProfileCham($this->id);
            if ($viewProfileCham->getResult()) {
                $this->data['viewProfileCham'] = $viewProfileCham->getResultBd();
                $this->loadViewProfileCham();
            } else {
                $urlRedirect = URLADM . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function loadViewProfileCham(): void
    {
        $button = ['list_cham' => ['menu_controller' => 'list-cham', 'menu_metodo' => 'index'],
        'edit_profile_image_cham' => ['menu_controller' => 'edit-profile-image-cham', 'menu_metodo' => 'index'],
        ];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/chamados/viewProfileCham", $this->data);
        $loadView->loadView();
    }
}
