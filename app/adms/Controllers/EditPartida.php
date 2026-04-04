<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class EditPartida
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $this->id = (int) $id;

        if (empty($this->id) && empty($this->data['form']['id'])) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Partida não encontrada!</p>";
            header("Location: " . URLADM . "list-competicoes/index");
            exit;
        }

        $editPartida = new \App\adms\Models\AdmsEditPartida();

        // Variável para saber se estamos a tentar salvar o formulário
        $isSubmit = isset($this->data['form']['AdmsEditPartida']);

        // Se clicou no botão salvar
        if ($isSubmit) {
            unset($this->data['form']['AdmsEditPartida']);
            $editPartida->update($this->data['form']);

            // Se salvou com sucesso, redireciona
            if ($editPartida->getResult()) {
                $compId = $editPartida->getCompeticaoId();
                if ($_SESSION['adms_access_level_id'] == 15) {
                    header("Location: " . URLADM . "meus-jogos/index/{$compId}");
                } else {
                    header("Location: " . URLADM . "view-competicao/index/{$compId}");
                }
                exit;
            }
            // SE FALHOU (erro de regra de pontuação), o script continua rodando para baixo.
            // Os dados digitados já estão seguros dentro de $this->data['form'] e voltarão para a tela!
        } 
        
        // Se NÃO foi submissão (ou seja, é o primeiro acesso à página), carrega os dados do banco
        if (!$isSubmit) {
            $editPartida->getPartida($this->id);
            if ($editPartida->getResultBd()) {
                $this->data['form'] = $editPartida->getResultBd()[0];
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Partida não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
                exit;
            }
        }

        $this->data['atletas'] = $editPartida->listAtletas();
        $this->data['sidebarActive'] = "list-competicoes";
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/partida/editPartida", $this->data);
        $loadView->loadView();
    }
}