<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditAtleta
{
    private array|string|null $data = null;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        // Recebe os dados do formulário de edição
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->id) && !empty($this->data['AdmsEditAtleta'])) {
            unset($this->data['AdmsEditAtleta']);
            $this->data['id'] = $this->id;
            
            $editAtleta = new \App\adms\Models\AdmsEditAtleta();
            $editAtleta->update($this->data);

            if ($editAtleta->getResult()) {
                $urlRedirect = URLADM . "list-atletas/index";
                header("Location: $urlRedirect");
            } else {
                $this->viewEditAtleta();
            }
        } else {
            $this->viewEditAtleta();
        }
    }

    private function viewEditAtleta(): void
    {
        $editAtleta = new \App\adms\Models\AdmsEditAtleta();
        $editAtleta->viewAtleta($this->id);
        
        if ($editAtleta->getResult()) {

            $listMenu = new \App\adms\Models\helper\AdmsMenu();
            $this->data['menu'] = $listMenu->itemMenu(); 

            $this->data['form'] = $editAtleta->getResult()[0];
            $this->data['sidebarActive'] = "list-atletas";
            
            $loadView = new \Core\ConfigView("adms/Views/atleta/editAtleta", $this->data);
            $loadView->loadView();

            

            

        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não encontrado!</p>";
            $urlRedirect = URLADM . "list-atletas/index";
            header("Location: $urlRedirect");
        }
    }
}