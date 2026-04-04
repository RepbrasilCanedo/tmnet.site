<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar contrato
 */
class DeleteContratos
{
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $deleteContrato = new \App\adms\Models\AdmsDeleteContratos();
            $deleteContrato->deleteContrato($this->id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um contrato para apagar!</p>";
        }

        // Redireciona de volta para a tela de listagem
        $urlRedirect = URLADM . "list-contratos/index";
        header("Location: $urlRedirect");
        exit; // Segurança: Garante que o PHP pare de executar aqui
    }
}