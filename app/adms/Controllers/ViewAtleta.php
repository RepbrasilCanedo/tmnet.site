<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewAtleta
{
    private array|string|null $data;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        // Verifica se o formulário de upload de foto foi enviado
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->data['AdmsUploadFoto'])) {
            unset($this->data['AdmsUploadFoto']);
            // O arquivo vem em $_FILES, não em $_POST
            $this->uploadFoto();
        } else {
            $this->viewAtleta();
        }
    }

    private function viewAtleta(): void
    {
        $viewAtleta = new \App\adms\Models\AdmsViewAtleta();
        $viewAtleta->viewAtleta($this->id);
        
        if ($viewAtleta->getResult()) {
             $listMenu = new \App\adms\Models\helper\AdmsMenu();
            $this->data['menu'] = $listMenu->itemMenu(); 

            $this->data['atleta'] = $viewAtleta->getResult()[0];
            $this->data['sidebarActive'] = "list-atletas";

            $loadView = new \Core\ConfigView("adms/Views/atleta/viewAtleta", $this->data);
            $loadView->loadView();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não encontrado!</p>";
            $urlRedirect = URLADM . "list-atletas/index";
            header("Location: $urlRedirect");
        }
    }

    private function uploadFoto(): void
    {
        // Pega os dados do arquivo enviado
        $dataImage = $_FILES['imagem'];

        $uploadFoto = new \App\adms\Models\AdmsViewAtleta();
        $uploadFoto->uploadFotoAtleta($this->id, $dataImage);

        // Independente de sucesso ou erro, recarrega a página para mostrar a mensagem
        $urlRedirect = URLADM . "view-atleta/index/{$this->id}";
        header("Location: $urlRedirect");
    }
}