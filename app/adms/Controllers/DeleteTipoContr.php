<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar tipo de contrato
 */
class DeleteTipoContr
{
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            $deleteTipoContr = new \App\adms\Models\AdmsDeleteTipoContr();
            $deleteTipoContr->deleteTipoContr($this->id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um Tipo de Contrato para apagar!</p>";
        }

        // Redireciona sempre de volta para a listagem
        $urlRedirect = URLADM . "list-tipo-contr/index";
        header("Location: $urlRedirect");
        exit; // Segurança: Interrompe o script após o redirecionamento
    }
}