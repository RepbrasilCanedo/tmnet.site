<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewCompeticao
{
    private array|string|null $data;

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (!empty($id)) {
            $viewComp = new \App\adms\Models\AdmsViewCompeticao();
            $viewComp->viewCompeticao($id);
            $this->data['viewComp'] = $viewComp->getResult();

            if ($this->data['viewComp']['detalhes']) {
                $listMenu = new \App\adms\Models\helper\AdmsMenu();
                $this->data['menu'] = $listMenu->itemMenu();
                $this->data['sidebarActive'] = "list-competicoes";

                $loadView = new \Core\ConfigView("adms/Views/competicao/viewCompeticao", $this->data);
                $loadView->loadView();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
            }
        } else {
            header("Location: " . URLADM . "list-competicoes/index");
        }
    }
}