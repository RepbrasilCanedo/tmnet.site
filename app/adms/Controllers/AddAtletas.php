<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da página Adicionar Atleta
 */
class AddAtletas
{
    private array|string|null $data = null;

    public function index(): void
    {
        // Recebe os dados do formulário
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Verifica se o usuário clicou no botão de enviar
        if (!empty($this->data['AdmsAddAtleta'])) {
            unset($this->data['AdmsAddAtleta']); // Remove o botão do array de dados
            
            $addAtleta = new \App\adms\Models\AdmsAddAtletas();
            $addAtleta->createAtleta($this->data);

            if ($addAtleta->getResult()) {
                // Se deu certo, redireciona para a listagem (que criaremos depois)
                $urlRedirect = URLADM . "list-atletas/index";
                header("Location: $urlRedirect");
            } else {
                // Se deu erro, mantém os dados no formulário para o usuário não perder o que digitou
                $this->viewAddAtleta();
            }
        } else {
            $this->viewAddAtleta();
        }
    }

    private function viewAddAtleta(): void
    {
         $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-atletas"; 
        
        $loadView = new \Core\ConfigView("adms/Views/atleta/addAtleta", $this->data);
        $loadView->loadView();
    }
}