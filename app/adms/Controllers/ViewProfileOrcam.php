<?php

namespace App\adms\Controllers;

class ViewProfileOrcam
{
    private array|string|null $data = [];
    private array|null $dataForm;

    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $idOrcam = (int)$id;

        $model = new \App\adms\Models\AdmsViewProfileOrcam();

        // Se clicou no botão de anexar
        if (!empty($this->dataForm['SendAnexarOrcam'])) {
            $model->viewProfileOrcam($idOrcam); // Carrega dados atuais primeiro
            $model->update($this->dataForm);
            if ($model->getResult()) {
                header("Location: " . URLADM . "view-orcam/index/$idOrcam");
                exit();
            }
        }

        $model->viewProfileOrcam($idOrcam);
        if ($model->getResult()) {
            $this->data['viewProfileOrcam'] = $model->getResultBd();
            $this->loadPage();
        } else {
            header("Location: " . URLADM . "list-orcam/index");
        }
    }

    private function loadPage(): void
    {
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'list_orcam' => ['menu_controller' => 'list-orcam', 'menu_metodo' => 'index']
        ]);
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
        $this->data['sidebarActive'] = "list-orcam";
        (new \Core\ConfigView("adms/Views/orcamentos/viewProfileOrcam", $this->data))->loadView();
    }
}