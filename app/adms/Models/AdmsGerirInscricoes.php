<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsGerirInscricoes
{
    private bool $result = false;
    private array|null $resultBd;
    private array|null $torneiosClube;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }
    function getTorneiosClube(): array|null { return $this->torneiosClube; }

    public function listarTorneiosDoClube(): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead(
            "SELECT id, nome_torneio, status_inscricao, data_evento 
             FROM adms_competicoes 
             WHERE empresa_id = :empresa AND status_inscricao = 1
             ORDER BY id DESC", 
            "empresa={$_SESSION['emp_user']}"
        );
        $this->torneiosClube = $read->getResult() ?: [];
    }

    public function listarInscritos(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // DOCAN FIX: Busca TODOS os 6 valores configurados no torneio
        $read->fullRead(
            "SELECT valor_uma_categoria, valor_duas_categorias, 
                    valor_uma_socio, valor_duas_socio, 
                    valor_uma_estudante, valor_duas_estudante 
             FROM adms_competicoes 
             WHERE id = :id AND empresa_id = :empresa LIMIT 1", 
             "id={$compId}&empresa={$_SESSION['emp_user']}"
        );
        $comp = $read->getResult();
        
        if (!$comp) { 
            $this->resultBd = []; 
            return; 
        }

        $valores = $comp[0];

        // DOCAN FIX: Busca a modalidade escolhida (tipo_inscricao) pelo atleta
        $read->fullRead(
            "SELECT i.adms_user_id, i.status_pagamento_id, i.tipo_inscricao, u.name as atleta, u.telefone, c.nome as categoria 
             FROM adms_inscricoes i
             INNER JOIN adms_users u ON u.id = i.adms_user_id
             INNER JOIN adms_categorias c ON c.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id", 
            "comp_id={$compId}"
        );
        $inscricoes = $read->getResult() ?: [];

        $agrupado = [];
        foreach ($inscricoes as $i) {
            $uid = $i['adms_user_id'];
            if (!isset($agrupado[$uid])) {
                $agrupado[$uid] = [
                    'user_id' => $uid,
                    'atleta' => $i['atleta'],
                    'telefone_limpo' => preg_replace('/[^0-9]/', '', $i['telefone']), 
                    'telefone_display' => $i['telefone'],
                    'status_pagamento_id' => $i['status_pagamento_id'],
                    'tipo_inscricao' => $i['tipo_inscricao'] ?? 'Geral', // Salva a modalidade aqui
                    'categorias' => []
                ];
            }
            $agrupado[$uid]['categorias'][] = $i['categoria'];
        }

        // DOCAN FIX: Calcula o total a pagar baseado na Modalidade Escolhida!
        foreach ($agrupado as $k => $a) {
            $qtdCategorias = count($a['categorias']);
            $tipo = $a['tipo_inscricao'];
            
            $val1 = (float)$valores['valor_uma_categoria'];
            $val2 = (float)$valores['valor_duas_categorias'];
            
            if ($tipo === 'Socio') {
                $val1 = (float)$valores['valor_uma_socio'];
                $val2 = (float)$valores['valor_duas_socio'];
            } elseif ($tipo === 'Estudante') {
                $val1 = (float)$valores['valor_uma_estudante'];
                $val2 = (float)$valores['valor_duas_estudante'];
            }
            
            $total = ($qtdCategorias == 1) ? $val1 : (($qtdCategorias >= 2) ? $val2 : 0);
            
            $agrupado[$k]['valor_total'] = $total;
            $agrupado[$k]['categorias_str'] = implode(' <br> ', $a['categorias']);
        }

        usort($agrupado, function($a, $b) {
            if ($a['status_pagamento_id'] == $b['status_pagamento_id']) {
                return strcmp($a['atleta'], $b['atleta']);
            }
            return $a['status_pagamento_id'] - $b['status_pagamento_id'];
        });

        $this->resultBd = $agrupado;
    }

    public function alterarStatusPagamento(int $userId, int $compId, int $novoStatus): void
    {
        $nivelLogado = (int)$_SESSION['adms_access_level_id'];
        $empresaId = (int)$_SESSION['emp_user'];

        $read = new \App\adms\Models\helper\AdmsRead();

        if ($nivelLogado > 2) {
            $read->fullRead("SELECT id FROM adms_competicoes WHERE id = :id AND empresa_id = :emp LIMIT 1", "id={$compId}&emp={$empresaId}");
            if (!$read->getResult()) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro de permissão. Este torneio não pertence ao seu clube.</p>";
                $this->result = false;
                return;
            }
        }

        $read->fullRead("SELECT id FROM adms_inscricoes WHERE adms_competicao_id = :comp AND adms_user_id = :user", "comp={$compId}&user={$userId}");
        $inscricoesDoAtleta = $read->getResult();

        if ($inscricoesDoAtleta) {
            $dados = ['status_pagamento_id' => $novoStatus];
            $up = new \App\adms\Models\helper\AdmsUpdate();

            $sucesso = false;
            foreach ($inscricoesDoAtleta as $ins) {
                $up->exeUpdate("adms_inscricoes", $dados, "WHERE id = :id", "id={$ins['id']}");
                if ($up->getResult()) {
                    $sucesso = true;
                }
            }

            if ($sucesso) {
                $_SESSION['msg'] = "<p class='alert-success'>Status de pagamento atualizado com sucesso!</p>";
                $this->result = true;

                if ($novoStatus == 2) {
                    $readFiliacao = new \App\adms\Models\helper\AdmsRead();
                    $readFiliacao->fullRead(
                        "SELECT id FROM adms_atleta_clube WHERE adms_user_id = :user_id AND empresa_id = :empresa_id LIMIT 1",
                        "user_id={$userId}&empresa_id={$empresaId}"
                    );

                    if (!$readFiliacao->getResult()) {
                        $createFiliacao = new \App\adms\Models\helper\AdmsCreate();
                        $dadosFiliacao = [
                            'adms_user_id' => $userId,
                            'empresa_id'   => $empresaId,
                            'created'      => date("Y-m-d H:i:s")
                        ];
                        $createFiliacao->exeCreate("adms_atleta_clube", $dadosFiliacao);
                    }
                }
                
            } else {
                $_SESSION['msg'] = "<p class='alert-warning'>Nenhuma alteração foi feita no banco (O status já era este).</p>";
                $this->result = true; 
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Inscrição não encontrada no banco de dados.</p>";
            $this->result = false;
        }
    }
}