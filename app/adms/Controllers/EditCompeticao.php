<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditCompeticao
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (empty($this->id)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $editComp = new \App\adms\Models\AdmsEditCompeticao();

        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->data['form']['SendEditComp'])) {
            $this->data['form']['categorias_selecionadas'] = $_POST['categorias_selecionadas'] ?? [];
            
            // DOCAN FIX: Recebe o ficheiro PDF enviado pelo formulário
            $this->data['form']['regulamento'] = $_FILES['regulamento'] ?? null;
            
            $editComp->update($this->data['form']);
            
            if ($editComp->getStatus()) {
                header("Location: " . URLADM . "view-competicao/index/{$this->data['form']['id']}");
                exit;
            }
        } else {
            $editComp->viewCompeticao($this->id);
            if ($editComp->getResult()) {
                $this->data['form'] = $editComp->getResult()[0];
                $this->data['form']['categorias_selecionadas'] = explode(',', $this->data['form']['categorias_selecionadas'] ?? '');
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
                exit;
            }
        }

        $this->data['categorias'] = $editComp->listarCategorias();
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/editCompeticao", $this->data);
        $loadView->loadView();
    }
}