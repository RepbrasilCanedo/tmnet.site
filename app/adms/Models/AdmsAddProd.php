<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar Produtos no banco de dados com Validação de Contrato e Limite
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddProd
{
    private array|null $data;
    private bool $result = false;
    private array $listRegistryAdd;

    function getResult(): bool
    {
        return $this->result;
    }

    public function create(array $data)
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        
        if ($valEmptyField->getResult()) {
            
            // 1. Nova Regra de Negócio: Valida Contrato e Limite de Equipamentos
            if ($this->valContractAndLimit((int)$this->data['cliente_id'])) {
                $this->add();
            } else {
                $this->result = false;
            }
            
        } else {
            $this->result = false;
        }
    }

    /**
     * Verifica se o cliente tem contrato ativo e valida o limite se for Hardware
     */
    private function valContractAndLimit(int $cliente_id): bool
    {
        $hoje = date('Y-m-d');
        $readContrato = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca o contrato ativo deste cliente
        $readContrato->fullRead(
            "SELECT id, tipo, quant FROM adms_contrato 
             WHERE cliente_id = :cliente_id AND status = 1 
             AND (inicio_contr <= :hoje OR inicio_contr IS NULL) 
             AND (final_contr >= :hoje OR final_contr IS NULL OR final_contr = '0000-00-00') 
             LIMIT 1",
            "cliente_id={$cliente_id}&hoje={$hoje}"
        );

        $contrato = $readContrato->getResult();

        // Se não encontrou contrato válido
        if (!$contrato) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ação bloqueada! Este cliente não possui um contrato ativo ou dentro do período de validade.</p>";
            return false;
        }

        // 2. Verifica se o contrato é de Hardware (Tipo = 1) e avalia o limite
        $tipoContrato = (int)$contrato[0]['tipo'];
        $limiteEquipamentos = (int)$contrato[0]['quant'];

        if ($tipoContrato === 1) { // 1 = Hardware (Verificação de Limite)
            $readProd = new \App\adms\Models\helper\AdmsRead();
            $readProd->fullRead("SELECT id FROM adms_produtos WHERE cliente_id = :cliente_id", "cliente_id={$cliente_id}");
            
            $totalCadastrado = 0;
            if ($readProd->getResult()) {
                $totalCadastrado = count($readProd->getResult());
            }

            // Se o número de equipamentos no banco já atingiu ou ultrapassou a quantidade do contrato
            if ($totalCadastrado >= $limiteEquipamentos) {
                $_SESSION['msg'] = "<p class='alert-warning'>Ação Bloqueada: Limite excedido ({$limiteEquipamentos} equipamentos). Contate a empresa para ampliar o número de equipamentos cobertos pelo contrato.</p>";
                return false;
            }
        }

        return true; // Passou em todas as validações
    }

    private function add(): void
    {
        date_default_timezone_set('America/Bahia');
        
        $this->data['created'] = date("Y-m-d H:i:s");
        $this->data['empresa_id'] = $_SESSION['emp_user']; 

        $createEquip = new \App\adms\Models\helper\AdmsCreate();
        $createEquip->exeCreate("adms_produtos", $this->data);

        if ($createEquip->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Equipamento cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
        $registry['sit_equip'] = $list->getResult();

        $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_equip ORDER BY name ASC");
        $registry['type_equip'] = $list->getResult();            

        $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes WHERE empresa= :empresa ORDER BY nome_fantasia ASC", "empresa={$_SESSION['emp_user']}");
        $registry['emp_equip'] = $list->getResult();

        // O Select de Contratos foi removido daqui para deixar a tela limpa
        $this->listRegistryAdd = [
            'type_equip' => $registry['type_equip'], 
            'emp_equip' => $registry['emp_equip'], 
            'sit_equip' => $registry['sit_equip']
        ];
        
        return $this->listRegistryAdd;
    }
}