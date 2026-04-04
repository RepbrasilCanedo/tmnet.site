<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class DeletePartida
{
    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $deletePartida = new \App\adms\Models\AdmsDeletePartida();
            $deletePartida->deletePartida((int)$id);
            
            if ($deletePartida->getResult()) {
                // Pega o ID da competição para voltar para a súmula correta
                $compId = $deletePartida->getCompeticaoId();
                header("Location: " . URLADM . "view-competicao/index/" . $compId);
            } else {
                header("Location: " . URLADM . "list-competicoes/index");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma partida selecionada para exclusão!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
        }
    }
}