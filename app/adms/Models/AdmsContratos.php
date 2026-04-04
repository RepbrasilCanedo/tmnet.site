<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

use App\adms\Models\helper\AdmsRead;
use App\adms\Models\helper\AdmsCreate;
use App\adms\Models\helper\AdmsUpdate;
use App\adms\Models\helper\AdmsDelete;

class AdmsContratos
{
    private $resultado;
    private $dados;
    private $msg;

    /**
     * Retorna o resultado da consulta
     * @return array|null
     */

    function getResult()
    {
        return $this->resultado;
    }
    
    function getMsg()
    {
        return $this->msg;
    }

    /** * LISTAR: Busca contratos apenas da empresa logada 
     */
    public function listar($empresaId)
    {
        $admsRead = new AdmsRead();
        // Ajuste 'adms_clientes' conforme o nome real da sua tabela de clientes
        $admsRead->fullRead(
            "SELECT c.*, cli.nome as cliente_nome 
             FROM adms_contratos c
             INNER JOIN adms_clientes cli ON cli.id = c.cliente_id
             WHERE c.empresa_id = :empresa_id 
             ORDER BY c.id DESC",
            "empresa_id={$empresaId}"
        );
        $this->resultado = $admsRead->getResult();
        return $this->resultado;
    }

    /** * CADASTRAR: Insere o cabeçalho do contrato
     */
    public function cadastrar(array $dados)
    {
        $this->dados = $dados;
        
        // Tratamento de checkbox (se não vier marcado, define 0)
        $this->dados['cobre_todos_equipamentos'] = isset($this->dados['cobre_todos_equipamentos']) ? 1 : 0;
        
        $this->dados['created'] = date("Y-m-d H:i:s");
        $this->dados['status_id'] = 1;

        $cadContrato = new AdmsCreate();
        $cadContrato->exeCreate("adms_contratos", $this->dados);

        if ($cadContrato->getResult()) {
            $this->resultado = $cadContrato->getResult(); // Retorna o ID
            return true;
        } else {
            $this->resultado = false;
            return false;
        }
    }

    /** * VER: Detalhes de um contrato específico (com segurança de empresa)
     */
    public function ver($id, $empresaId)
    {
        $admsRead = new AdmsRead();
        $admsRead->fullRead(
            "SELECT * FROM adms_contratos WHERE id = :id AND empresa_id = :empresa_id LIMIT 1",
            "id={$id}&empresa_id={$empresaId}"
        );
        $this->resultado = $admsRead->getResult();
        return $this->resultado ? $this->resultado[0] : null;
    }

    /**
     * SYNC EQUIPAMENTOS: Atualiza a lista de equipamentos cobertos (N:N)
     */
    public function syncEquipamentos($contratoId, array $equipamentosIds)
    {
        // 1. Apaga vínculos antigos
        $deletar = new AdmsDelete();
        $deletar->exeDelete("adms_contrato_equipamentos", "WHERE adms_contrato_id = :id", "id={$contratoId}");

        // 2. Insere novos
        if (!empty($equipamentosIds)) {
            $cadastrar = new AdmsCreate();
            foreach ($equipamentosIds as $equipId) {
                $dadosLink = [
                    'adms_contrato_id' => $contratoId,
                    'adms_equipamento_id' => $equipId,
                    'created' => date("Y-m-d H:i:s")
                ];
                $cadastrar->exeCreate("adms_contrato_equipamentos", $dadosLink);
            }
        }
        $this->resultado = true;
    }

    /**
     * AUXILIAR: Retorna array de IDs dos equipamentos já vinculados
     */
    public function listaIdsEquipamentosVinculados($contratoId)
    {
        $admsRead = new AdmsRead();
        $admsRead->fullRead(
            "SELECT adms_equipamento_id FROM adms_contrato_equipamentos WHERE adms_contrato_id = :id",
            "id={$contratoId}"
        );
        
        $ids = [];
        if ($admsRead->getResult()) {
            foreach ($admsRead->getResult() as $item) {
                $ids[] = $item['adms_equipamento_id'];
            }
        }
        return $ids;
    }

    /**
     * CORE: Validação para abertura de chamado
     */
    public function validarPermissaoAtendimento($empresaId, $clienteId, $equipamentoId = null)
    {
        $dataAtual = date('Y-m-d');
        
        $admsRead = new AdmsRead();
        $admsRead->fullRead(
            "SELECT id, cobre_todos_equipamentos 
             FROM adms_contratos 
             WHERE empresa_id = :emp_id AND cliente_id = :cli_id AND status_id = 1
             AND :hoje BETWEEN data_inicio AND data_fim",
            "emp_id={$empresaId}&cli_id={$clienteId}&hoje={$dataAtual}"
        );

        $contratos = $admsRead->getResult();

        if (!$contratos) {
            return ['status' => false, 'msg' => 'Cliente sem contrato vigente.'];
        }

        // Se for chamado geral (sem equipamento), libera
        if (empty($equipamentoId)) {
            return ['status' => true, 'contrato_id' => $contratos[0]['id']];
        }

        // Valida equipamento específico
        foreach ($contratos as $c) {
            if ($c['cobre_todos_equipamentos'] == 1) {
                return ['status' => true, 'contrato_id' => $c['id']];
            }

            // Verifica vínculo
            $check = new AdmsRead();
            $check->fullRead(
                "SELECT id FROM adms_contrato_equipamentos 
                 WHERE adms_contrato_id = :cid AND adms_equipamento_id = :eid LIMIT 1",
                "cid={$c['id']}&eid={$equipamentoId}"
            );
            
            if ($check->getResult()) {
                return ['status' => true, 'contrato_id' => $c['id']];
            }
        }

        return ['status' => false, 'msg' => 'Equipamento fora da cobertura contratual.'];
    }
}