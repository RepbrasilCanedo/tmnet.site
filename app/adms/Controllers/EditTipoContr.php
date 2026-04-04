<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar tipo de contrato
 */
class EditTipoContr
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            if (!empty($this->dataForm['SendEditTipoContr'])) {
                unset($this->dataForm['SendEditTipoContr']);
                
                $editTipoContr = new \App\adms\Models\AdmsEditTipoContr();
                $editTipoContr->update($this->dataForm);
                
                if ($editTipoContr->getResult()) {
                    $urlRedirect = URLADM . "list-tipo-contr/index";
                    header("Location: $urlRedirect");
                    exit;
                } else {
                    $this->data['form'] = $this->dataForm;
                    $this->viewEditTipoContr();
                }
            } else {
                $viewTipoContr = new \App\adms\Models\AdmsEditTipoContr();
                $viewTipoContr->viewTipoContr($this->id);
                
                if ($viewTipoContr->getResult()) {
                    $this->data['form'] = $viewTipoContr->getResultBd()[0];
                    $this->viewEditTipoContr();
                } else {
                    $urlRedirect = URLADM . "list-tipo-contr/index";
                    header("Location: $urlRedirect");
                    exit;
                }
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de Contrato não encontrado!</p>";
            $urlRedirect = URLADM . "list-tipo-contr/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    private function viewEditTipoContr(): void
    {
        $button = [
            'list_tipo_contr' => ['menu_controller' => 'list-tipo-contr', 'menu_metodo' => 'index'],
            'view_tipo_contr' => ['menu_controller' => 'view-tipo-contr', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-tipo-contr"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/editTipoContr", $this->data);
        $loadView->loadView();
    }
}