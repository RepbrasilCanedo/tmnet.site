<?php
namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class Chat {
    private array|string|null $data = [];

    public function index(): void {
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
        $readUser = new \App\adms\Models\helper\AdmsRead();
        $readUser->fullRead("SELECT id, name, image FROM adms_users 
                             WHERE (adms_access_level_id = 4 OR adms_access_level_id = 12) 
                             AND id != :me AND adms_sits_user_id = 1 
                             AND empresa_id = :emp ORDER BY name ASC", 
                             "me={$_SESSION['user_id']}&emp={$_SESSION['emp_user']}");
        $this->data['usuarios'] = $readUser->getResult() ?? [];
        (new \Core\ConfigView("adms/Views/chat/chat", $this->data))->loadView();
    }

    public function carregarMensagens(): void {
        $url = explode("/", filter_input(INPUT_GET, 'url', FILTER_DEFAULT));
        $id_dest = (int) (end($url)); 
        if ($id_dest > 0) {
            echo json_encode((new \App\adms\Models\AdmsChat())->listarMensagens($id_dest));
        } else {
            echo json_encode([]);
        }
        exit;
    }

    public function enviar(): void {
        $msg = filter_input(INPUT_POST, 'mensagem', FILTER_DEFAULT);
        $dest = filter_input(INPUT_POST, 'destinatario_id', FILTER_VALIDATE_INT);
        $id_editar = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        header('Content-Type: application/json');
        if ($msg && $dest) {
            $model = new \App\adms\Models\AdmsChat();
            $id_editar ? $model->editarMensagem($id_editar, $msg) : $model->enviarMensagem(['destinatario_id' => $dest, 'mensagem' => $msg]);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
        exit;
    }

    public function verificarNovas(): void
        {
            $model = new \App\adms\Models\AdmsChat();
            
            // Primeiro: Registra que EU estou ativo agora
            $model->atualizarAtividade();

            // Segundo: Busca quem mais deu sinal de vida nos últimos 30 segundos
            $onlineIds = $model->buscarUsuariosOnline();

            $read = new \App\adms\Models\helper\AdmsRead();
            $read->fullRead("SELECT remetente_id, COUNT(id) as total 
                            FROM adms_chats 
                            WHERE destinatario_id = :me AND lida = 0 
                            GROUP BY remetente_id", 
                            "me={$_SESSION['user_id']}");
            
            header('Content-Type: application/json');
            echo json_encode([
                'notificacoes' => $read->getResult() ?? [],
                'online_ids' => $onlineIds
            ]);
            exit;
        }

    public function limparHistorico(): void {
        $url = explode("/", filter_input(INPUT_GET, 'url', FILTER_DEFAULT));
        $id_dest = (int) end($url);
        if ($id_dest > 0) {
            (new \App\adms\Models\AdmsChat())->apagarConversa($id_dest);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
        exit;
    }

    public function apagarMensagem(): void {
        $url = explode("/", filter_input(INPUT_GET, 'url', FILTER_DEFAULT));
        $id_msg = (int) end($url);
        if ($id_msg > 0) {
            (new \App\adms\Models\AdmsChat())->apagarMensagemIndividual($id_msg);
            echo json_encode(['status' => true]);
        } else {
            echo json_encode(['status' => false]);
        }
        exit;
    }
}