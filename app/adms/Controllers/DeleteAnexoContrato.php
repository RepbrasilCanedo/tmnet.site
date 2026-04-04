<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar anexo do contrato
 */
class DeleteAnexoContrato
{
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {
            // Trava de Segurança Extra na Controller: Confirma se o usuário logado é ADM (Nível 4)
            if (isset($_SESSION['adms_access_level_id']) && $_SESSION['adms_access_level_id'] == 4) {
                
                $deleteAnexo = new \App\adms\Models\AdmsDeleteAnexoContrato();
                $deleteAnexo->deleteAnexo($this->id);

                // Pega o ID do contrato para voltar à página correta
                $contId = $deleteAnexo->getContId();
                
                if ($contId) {
                    $urlRedirect = URLADM . "view-contratos/index/" . $contId;
                } else {
                    $urlRedirect = URLADM . "list-contratos/index";
                }

            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ação bloqueada! Apenas Administradores podem apagar anexos.</p>";
                // Se um usuário normal tentar forçar a URL, joga ele para a lista
                $urlRedirect = URLADM . "list-contratos/index"; 
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um anexo para apagar!</p>";
            $urlRedirect = URLADM . "list-contratos/index";
        }

        header("Location: $urlRedirect");
        exit;
    }
}