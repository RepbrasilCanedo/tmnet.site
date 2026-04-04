<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewChave
{
    private array|string|null $data;

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (!empty($id)) {
            $viewChave = new \App\adms\Models\AdmsViewChave();
            $viewChave->viewChave($id);
            $this->data['viewChave'] = $viewChave->getResult();

            if ($this->data['viewChave']['detalhes']) {
                $listMenu = new \App\adms\Models\helper\AdmsMenu();
                $this->data['menu'] = $listMenu->itemMenu();
                $this->data['sidebarActive'] = "list-competicoes";

                // Passa o ID da competição para o botão voltar da View
                $this->data['competicao_id'] = $id;

                $loadView = new \Core\ConfigView("adms/Views/competicao/viewChave", $this->data);
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