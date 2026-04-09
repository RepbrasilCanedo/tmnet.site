<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class NovoCredenciamento
{
    private array|string|null $data = [];

    public function index(): void
    {
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        $credenciamento = new \App\adms\Models\AdmsNovoCredenciamento();

        if (!empty($this->data['form']['SendNovaConta'])) {
            $credenciamento->criarCadastro($this->data['form']);
            
            if ($credenciamento->getResult()) {
                // Se deu sucesso, redireciona para o Login para o atleta ver a mensagem verde
                header("Location: " . URLADM . "login/index");
                exit;
            }
        }

        // Busca a lista de clubes para o Dropdown
        $this->data['clubes'] = $credenciamento->listarClubes();

        // Carrega a View da página de Credenciamento
        $loadView = new \Core\ConfigView("adms/Views/login/novoCredenciamento", $this->data);
        // Usamos loadViewLogin() porque esta tela usa o layout aberto, sem barra lateral!
        $loadView->loadViewLogin(); 
    }
}