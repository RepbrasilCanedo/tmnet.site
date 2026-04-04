<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {header("Location: /");die ("Erro: Página não encontrada<br>");}

class Contratos
{
    private $dados;

    public function index()
    {
        $empresaId = $_SESSION['usuario_empresa_id'];
        
        $model = new \App\adms\Models\AdmsContratos();
        $this->dados['listContracts'] = $model->listar($empresaId);

        $carregarView = new \Core\ConfigView("adms/Views/contratos/listarContratos", $this->dados);
        $carregarView->loadView();
    }

    public function cadastrar()
    {
        $this->dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dados['SendCadContrato'])) {
            unset($this->dados['SendCadContrato']);
            
            // Segurança: Força o ID da empresa da sessão
            $this->dados['empresa_id'] = $_SESSION['user'];

            $model = new \App\adms\Models\AdmsContratos();
            if ($model->cadastrar($this->dados)) {
                $novoId = $model->getResult();
                $_SESSION['msg'] = "<div class='alert alert-success'>Contrato cadastrado com sucesso!</div>";
                
                // Lógica de redirecionamento inteligente
                if (!isset($this->dados['cobre_todos_equipamentos'])) {
                    // Se não cobre tudo, vai para tela de vincular equipamentos
                    header("Location: " . URL . "contratos/equipamentos/$novoId");
                } else {
                    header("Location: " . URL . "contratos/index");
                }
            } else {
                $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao cadastrar contrato.</div>";
            }
        }

        // Carrega lista de clientes para o Select
        $modelCli = new \App\adms\Models\AdmsListEmpresas(); // Assumindo existência
        $this->dados['select_clientes'] = $modelCli->listEmpresas($_SESSION['user']);

        $loadView = new \Core\ConfigView("adms/Views/contratos/cadastrarContrato", $this->dados);
        $loadView->loadView();
    }

    public function equipamentos($contratoId = null)
    {
        $this->dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $contratoId = (int) $contratoId;

        if (!empty($this->dados['SendEditEquip'])) {
            unset($this->dados['SendEditEquip']);
            
            $model = new \App\adms\Models\AdmsContratos();
            // A view envia um array 'equipamentos[]'
            $listaIds = $this->dados['equipamentos'] ?? [];
            
            $model->syncEquipamentos($contratoId, $listaIds);
            
            $_SESSION['msg'] = "<div class='alert alert-success'>Lista de equipamentos atualizada!</div>";
            header("Location: " . URL . "contratos/equipamentos/$contratoId");
        }

        $model = new \App\adms\Models\AdmsContratos();
        $infoContrato = $model->ver($contratoId, $_SESSION['usuario_empresa_id']);
        
        if (!$infoContrato) {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Contrato não encontrado.</div>";
            header("Location: " . URL . "contratos/index");
            return;
        }

        $this->dados['contrato'] = $infoContrato;
        
        // Busca todos equipamentos do cliente para listar
        
        /*$modelEquip = new \App\adms\Models\AdmsListProd(); // Assumindo existência
        $this->dados['lista_completa'] = $modelEquip->listSelect('nome_clie');*/
        
        // Busca quais já estão marcados
        $this->dados['vinculados'] = $model->listaIdsEquipamentosVinculados($contratoId);

        $this->dados['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
        
        $this->dados['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'view_cham' => ['menu_controller' => 'view-cham', 'menu_metodo' => 'index'],
            'edit_cham' => ['menu_controller' => 'edit-cham', 'menu_metodo' => 'index']
        ]);

        $loadView = new \Core\ConfigView("adms/Views/contratos/editarEquipamentos", $this->dados);
        $loadView->loadView();
    }
}