<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die();
}

class ViewContato
{
    private array|string|null $data;

    public function index(string|int|null $id = null): void
    {
        $id_mens = (int) $id;
        

        if (!empty($id_mens)) {
            // 1. AUTOMAÇÃO: Se a mensagem estiver como 'Enviado', muda para 'Lido' ao abrir
            $updateStatus = new \App\adms\Models\helper\AdmsUpdate();
            $updateStatus->exeUpdate(
                "sts_contacts_msgs", 
                ["status" => "Lido"], 
                "WHERE id = :id AND status = 'Pendente'", 
                "id={$id_mens}"
            );

            // 2. Carrega os dados da mensagem
            $viewMsg = new \App\adms\Models\AdmsViewContato();
            $viewMsg->viewContato($id_mens);

            if ($viewMsg->getResult()) {
                $this->data['viewContato'] = $viewMsg->getResultBd();

                $button = ['list_contato' => ['menu_controller' => 'list-contato', 'menu_metodo' => 'index']];
                $listBotton = new \App\adms\Models\helper\AdmsButton();
                $this->data['button'] = $listBotton->buttonPermission($button);
                
                // Carrega permissões de botões e menu
                $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
                $this->data['sidebarActive'] = "list-contato";

                $loadView = new \Core\ConfigView("adms/Views/contato/viewContato", $this->data);
                $loadView->loadView();
            } else {
                $_SESSION['msg'] = "<div class='alert-danger'>Erro: Mensagem não encontrada!</div>";
                header("Location: " . URLADM . "list-contato/index");
            }
        } else {
            header("Location: " . URLADM . "list-contato/index");
        }
    }
}