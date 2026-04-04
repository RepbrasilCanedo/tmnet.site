<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditCategoria
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->id = (int) $id;

        if(!empty($this->id) && empty($this->dataForm['SendEditCategoria'])){
            $viewCat = new \App\adms\Models\AdmsEditCategoria();
            $viewCat->viewCategoria($this->id);
            if($viewCat->getResultBd()){
                $this->data['form'] = $viewCat->getResultBd()[0];
                $this->viewEditCategoria();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Categoria não encontrada.</p>";
                header("Location: " . URLADM . "list-categorias/index");
            }
        } elseif (!empty($this->dataForm['SendEditCategoria'])) {
            unset($this->dataForm['SendEditCategoria']);
            $upCat = new \App\adms\Models\AdmsEditCategoria();
            $upCat->update($this->dataForm);
            if($upCat->getResult()){
                header("Location: " . URLADM . "list-categorias/index");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditCategoria();
            }
        } else {
            header("Location: " . URLADM . "list-categorias/index");
        }
    }

    private function viewEditCategoria(): void
    {
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        $this->data['sidebarActive'] = "list-categorias"; 
        $loadView = new \Core\ConfigView("adms/Views/categoria/editCategoria", $this->data);
        $loadView->loadView();
    }
}