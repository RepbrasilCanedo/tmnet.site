<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerirInscricoes
{
    private array|string|null $data = [];

    public function index(): void
    {
        // 1. Verificações de Segurança (Apenas Organização/Admins)
        if (empty($_SESSION['user_id'])) {
            header("Location: " . URLADM . "login/index");
            exit;
        }

        if ($_SESSION['adms_access_level_id'] >= 14) {
            $_SESSION['msg'] = "<p class='alert-danger'>Acesso restrito. Você não tem permissão para gerir pagamentos.</p>";
            header("Location: " . URLADM . "dashboard/index");
            exit;
        }

        $model = new \App\adms\Models\AdmsGerirInscricoes();

        // 2. Processa Ações de Pagamento (Botões Aprovar / Desfazer)
        $postData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        
        // Se houver POST de Ação, processa e morre aqui, redirecionando o utilizador
        if (!empty($postData['AcaoStatus'])) {
            $uId = (int)$postData['user_id'];
            $cId = (int)$postData['comp_id'];
            
            if ($postData['AcaoStatus'] === 'aprovar') {
                $model->alterarStatusPagamento($uId, $cId, 2); // 2 = Pago/Confirmado
            } elseif ($postData['AcaoStatus'] === 'pendente') {
                $model->alterarStatusPagamento($uId, $cId, 1); // 1 = Aguardando
            }
            
            // Força o redirecionamento com um JavaScript seguro caso o header() do PHP bloqueie
            echo "<script>window.location.href = '" . URLADM . "gerir-inscricoes/index?comp=" . $cId . "';</script>";
            exit;
        }

        // 3. Pega o ID do torneio (vindo da URL via GET)
        $torneioId = filter_input(INPUT_GET, 'comp', FILTER_SANITIZE_NUMBER_INT);
        $this->data['torneio_selecionado'] = !empty($torneioId) ? (int)$torneioId : null;

        // 4. Carrega as informações para a View
        $model->listarTorneiosDoClube();
        $this->data['torneios'] = $model->getTorneiosClube();

        if ($this->data['torneio_selecionado']) {
            $model->listarInscritos($this->data['torneio_selecionado']);
            $this->data['inscritos'] = $model->getResultBd();
        }

        $this->data['sidebarActive'] = "gerir-inscricoes"; 
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $loadView = new \Core\ConfigView("adms/Views/competicao/gerirInscricoes", $this->data);
        $loadView->loadView();
    }
}