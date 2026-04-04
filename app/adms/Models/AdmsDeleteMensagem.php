<?php
namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsDeleteMensagem {
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function deleteMensagem(int $id): void {
        // 1. Verifica se a mensagem existe E se o status é 'Lido'
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id FROM sts_contacts_msgs WHERE id = :id AND status = 'Lido' LIMIT 1", "id={$id}");
        
        if ($read->getResult()) {
            $delete = new \App\adms\Models\helper\AdmsDelete();
            $delete->exeDelete("sts_contacts_msgs", "WHERE id = :id", "id={$id}");
            
            if ($delete->getResult()) {
                $_SESSION['msg'] = "<div class='alert-success'>Mensagem excluída com sucesso!</div>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<div class='alert-danger'>Erro: Não foi possível excluir a mensagem no banco de dados.</div>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: A mensagem não pode ser excluída (deve estar como 'Lido').</div>";
            $this->result = false;
        }
    }
}