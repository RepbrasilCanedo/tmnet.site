<?php
namespace App\adms\Controllers;
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class EditEmpresas
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) && (empty($this->dataForm['SendEditEmpresas']))) {
            $this->id = (int) $id;
            $viewEmpresas = new \App\adms\Models\AdmsEditEmpresas();
            $viewEmpresas->viewEmpresas($this->id);
            if ($viewEmpresas->getResult()) {
                $this->data['form'] = $viewEmpresas->getResultBd();
                $this->viewEditEmpresas();
            } else {
                header("Location: " . URLADM . "list-empresas/index");
            }
        } else {
            $this->editEmpresas();
        }
    }

    private function viewEditEmpresas(): void
    {
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'list_empresas' => ['menu_controller' => 'list-empresas', 'menu_metodo' => 'index'],
            'view_empresas' => ['menu_controller' => 'view-empresas', 'menu_metodo' => 'index']
        ]);

        $model = new \App\adms\Models\AdmsEditEmpresas();
        $this->data['select'] = $model->listSelect();
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu(); 
        $this->data['sidebarActive'] = "list-empresas";
        
        (new \Core\ConfigView("adms/Views/empresas/editEmpresas", $this->data))->loadView();
    }

    private function editEmpresas(): void
    {
        if (!empty($this->dataForm['SendEditEmpresas'])) {
            unset($this->dataForm['SendEditEmpresas']);
            $editEmpresas = new \App\adms\Models\AdmsEditEmpresas();
            $editEmpresas->update($this->dataForm);
            if ($editEmpresas->getResult()) {
                header("Location: " . URLADM . "view-empresas/index/" . $this->dataForm['id']);
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditEmpresas();
            }
        } else {
            header("Location: " . URLADM . "list-empresas/index");
        }
    }
}