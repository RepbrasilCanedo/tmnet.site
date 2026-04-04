<?php
namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class DeleteMensagem {
    public function index(int|string|null $id = null): void {
        $id_mens = (int) $id;
        
        // Validação de Segurança: Somente Administrador (4) pode excluir
        if ($_SESSION['adms_access_level_id'] != 4) {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Você não tem permissão para excluir registros!</div>";
            header("Location: " . URLADM . "list-contato/index");
            exit();
        }

        if (!empty($id_mens)) {
            $model = new \App\adms\Models\AdmsDeleteMensagem();
            $model->deleteMensagem($id_mens);

            if ($model->getResult()) {
                header("Location: " . URLADM . "list-contato/index");
            } else {
                header("Location: " . URLADM . "list-contato/index");
            }
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Selecione uma mensagem!</div>";
            header("Location: " . URLADM . "list-contato/index");
        }
    }
}