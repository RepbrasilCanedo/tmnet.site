<?php
namespace App\adms\Controllers;
if(!defined('D0O8C0A3N1E9D6O1')){ header("Location: /"); die(); }

class AddEmpresas
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);   

        if(!empty($this->dataForm['SendAddEmpresas'])){
            unset($this->dataForm['SendAddEmpresas']);

            // Garante segurança de nível de acesso
            if (!in_array((int)$_SESSION['adms_access_level_id'], [1, 2])) {
                $this->dataForm['empresa'] = $_SESSION['emp_user'];
            }

            $createEmpresas = new \App\adms\Models\AdmsAddEmpresas();
            $createEmpresas->create($this->dataForm);

            if($createEmpresas->getResult()){
                header("Location: " . URLADM . "list-empresas/index");
                return;
            }
            $this->data['form'] = $this->dataForm;
        }
        $this->viewAddEmpresas();
    }

    private function viewAddEmpresas(): void
    {
        $model = new \App\adms\Models\AdmsAddEmpresas();
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'list_empresas' => ['menu_controller' => 'list-empresas', 'menu_metodo' => 'index']
        ]);
        $this->data['select'] = $model->listSelect();
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu(); 
        $this->data['sidebarActive'] = "list-empresas"; 
        
        (new \Core\ConfigView("adms/Views/empresas/addEmpresas", $this->data))->loadView();
    }
}