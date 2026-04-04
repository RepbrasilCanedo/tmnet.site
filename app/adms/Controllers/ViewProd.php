<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página visualizar detalhes da página
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ViewProd
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo visualizar detalhe da página
     */
public function index(int|string|null $id = null): void
{
    $this->id = (int) $id;
    
    // Captura ações de Upload e Exclusão
    $btn_upload = filter_input(INPUT_POST, 'SendUploadLaudo', FILTER_SANITIZE_SPECIAL_CHARS);
    $del_laudo = filter_input(INPUT_GET, 'del_laudo', FILTER_SANITIZE_NUMBER_INT);

    // Lógica de Exclusão com restrição de ID de Nível de Acesso
    // Suporte = 12, ADM = 4. Usuário Final = 14
    if (!empty($del_laudo)) {
        if ($_SESSION['adms_access_level_id'] == 12 || $_SESSION['adms_access_level_id'] == 4) {
            $deleteLaudo = new \App\adms\Models\AdmsViewProd();
            $deleteLaudo->deleteLaudo($del_laudo);
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Você não tem permissão para excluir laudos.</div>";
        }
        header("Location: " . URLADM . "view-prod/index/" . $this->id);
        exit;
    }

    if ($btn_upload) {
        $data_upload['produto_id'] = $this->id;
        $file_upload = $_FILES['laudo_pdf'];
        $uploadLaudo = new \App\adms\Models\AdmsViewProd();
        $uploadLaudo->uploadLaudo($data_upload, $file_upload);
        header("Location: " . URLADM . "view-prod/index/" . $this->id);
        exit;
    }

    if (!empty($this->id)) {
        $viewProd = new \App\adms\Models\AdmsViewProd();
        $viewProd->viewProd($this->id);
        
        if ($viewProd->getResult()) {
            $this->data['viewProd'] = $viewProd->getResultBd();
            // Carrega os laudos
            $this->data['listLaudos'] = $viewProd->listLaudos($this->id);
            $this->viewProd();
        } else {
            header("Location: " . URLADM . "list-prod/index");
        }
    }
}

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     */
    private function viewProd(): void
    {
        $listTable = new \App\adms\Models\AdmsViewProd();
        $this->data['list_table'] = $listTable->listTable(); 

        // ADICIONEI AQUI: 'view_cham' => ...
        $button = [
            'list_prod' => ['menu_controller' => 'list-prod', 'menu_metodo' => 'index'],
            'edit_prod' => ['menu_controller' => 'edit-prod', 'menu_metodo' => 'index'],
            'delete_prod' => ['menu_controller' => 'delete-prod', 'menu_metodo' => 'index'],
            'view_cham' => ['menu_controller' => 'view-cham', 'menu_metodo' => 'index'] // <--- Permissão Nova
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-prod";
        $loadView = new \Core\ConfigView("adms/Views/produtos/viewProd", $this->data);
        $loadView->loadView();
    }
}