<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class NovoClube
{
    private array|string|null $data = [];

    public function index(): void
    {
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->data['form']['SendNewClub'])) {
            $novoClube = new \App\adms\Models\AdmsNovoClube();
            $novoClube->create($this->data['form']);

            if ($novoClube->getResult()) {
                // Se der certo, manda para a tela de login com a mensagem verde
                header("Location: " . URLADM . "login/index");
                exit;
            } else {
                // Se der erro (ex: email repetido), carrega a página com o formulário preenchido
                $this->loadView();
            }
        } else {
            $this->loadView();
        }
    }

    private function loadView(): void
    {
        // DOCAN FIX: Como esta página é pública, carregamos o layout "login" que não tem sidebar nem menu superior
        $loadView = new \Core\ConfigView("adms/Views/login/novoClube", $this->data);
        $loadView->loadViewLogin(); 
    }
}