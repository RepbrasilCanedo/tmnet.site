<?php
namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsChat {
    private bool $result = false;
    private $resultBd;

    function getResult(): bool { return $this->result; }
    function getResultBd() { return $this->resultBd; }

    public function enviarMensagem(array $data): void {
        date_default_timezone_set('America/Bahia');
        $data['remetente_id'] = $_SESSION['user_id'];
        $data['created'] = date("Y-m-d H:i:s");
        $create = new \App\adms\Models\helper\AdmsCreate();
        $create->exeCreate("adms_chats", $data);
        $this->result = $create->getResult();
    }

    public function atualizarAtividade(): void {
        $update = new \App\adms\Models\helper\AdmsUpdate();
        $update->exeUpdate("adms_users", ["last_activity" => date("Y-m-d H:i:s")], "WHERE id = :id", "id={$_SESSION['user_id']}");
    }

    public function buscarUsuariosOnline(): array {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Consideramos offline se o último sinal foi há mais de 30 segundos
        // 2. Usamos UTC ou o horário do banco para evitar conflito de fuso horário
        $read->fullRead("SELECT id FROM adms_users 
                        WHERE last_activity > DATE_SUB(NOW(), INTERVAL 30 SECOND) 
                        AND empresa_id = :emp 
                        AND id != :me", 
                        "emp={$_SESSION['emp_user']}&me={$_SESSION['user_id']}");
                        
        $res = $read->getResult() ?? [];
        return array_column($res, 'id');
    }

    public function listarMensagens(int $destinatario_id): array {
        $remetente_id = $_SESSION['user_id'];
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, remetente_id, mensagem, DATE_FORMAT(created, '%H:%i') as hora 
                    FROM adms_chats 
                    WHERE (remetente_id = :rem AND destinatario_id = :dest) 
                    OR (remetente_id = :dest AND destinatario_id = :rem) 
                    ORDER BY created ASC", "rem={$remetente_id}&dest={$destinatario_id}");
        $resultado = $read->getResult() ?? [];

        $update = new \App\adms\Models\helper\AdmsUpdate();
        $update->exeUpdate("adms_chats", ["lida" => 1], 
            "WHERE remetente_id = :dest AND destinatario_id = :rem AND lida = 0", 
            "dest={$destinatario_id}&rem={$remetente_id}");
        return $resultado;
    }

    public function apagarConversa(int $destinatario_id): void {
        $meu_id = $_SESSION['user_id'];
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_chats", "WHERE (remetente_id = :me AND destinatario_id = :dest) OR (remetente_id = :dest AND destinatario_id = :me)", "me={$meu_id}&dest={$destinatario_id}");
    }

    public function editarMensagem(int $id, string $mensagem): void {
        $update = new \App\adms\Models\helper\AdmsUpdate();
        $update->exeUpdate("adms_chats", ["mensagem" => $mensagem], "WHERE id = :id AND remetente_id = :me", "id={$id}&me={$_SESSION['user_id']}");
        $this->result = $update->getResult();
    }

    public function apagarMensagemIndividual(int $id): void {
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_chats", "WHERE id = :id AND remetente_id = :me", "id={$id}&me={$_SESSION['user_id']}");
        $this->result = $delete->getResult();
    }
}