<?php
namespace App\adms\Controllers;
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die("Erro: Página não encontrada<br>"); }

class AltStatusInscricao
{
    public function index(int|string|null $id = null): void
    {
        // ==========================================================
        // TESTE DE FOGO: Descomente a linha abaixo (remova as //)
        //die("SUCESSO: O sistema chegou no Controller!");
        // ==========================================================

        $id = (int) $id;
        if (!empty($id)) {
            $altStatus = new \App\adms\Models\AdmsAltStatusInscricao();
            $altStatus->altStatus($id);
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: ID do torneio não foi enviado.</p>";
        }
        
        // Redireciona de volta para a súmula
        header("Location: " . URLADM . "view-competicao/index/{$id}");
        exit;
    }
}