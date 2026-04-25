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

    // Busca todos os torneios do Clube logado para o "Select" do topo da página
    public function listarTorneiosDoClube(): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        // ========================================================================
        // DOCAN FIX: Adicionado "AND status_inscricao = 1" para mostrar apenas os ativos!
        // ========================================================================
        $read->fullRead(
            "SELECT id, nome_torneio, status_inscricao, data_evento 
             FROM adms_competicoes 
             WHERE empresa_id = :empresa AND status_inscricao = 1
             ORDER BY id DESC", 
            "empresa={$_SESSION['emp_user']}"
        );
        $this->torneiosClube = $read->getResult() ?: [];
    }

    // Busca os inscritos de um torneio específico, agrupa e calcula os valores
    public function listarInscritos(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Pega os valores configurados no torneio
        $read->fullRead("SELECT valor_uma_categoria, valor_duas_categorias FROM adms_competicoes WHERE id = :id AND empresa_id = :empresa LIMIT 1", "id={$compId}&empresa={$_SESSION['emp_user']}");
        $comp = $read->getResult();
        
        if (!$comp) { 
            $this->resultBd = []; 
            return; 
        }

        $valUma = (float)$comp[0]['valor_uma_categoria'];
        $valDuas = (float)$comp[0]['valor_duas_categorias'];

        // 2. Busca todas as inscrições desse torneio
        $read->fullRead(
            "SELECT i.adms_user_id, i.status_pagamento_id, u.name as atleta, u.telefone, c.nome as categoria 
             FROM adms_inscricoes i
             INNER JOIN adms_users u ON u.id = i.adms_user_id
             INNER JOIN adms_categorias c ON c.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id", 
            "comp_id={$compId}"
        );
        $inscricoes = $read->getResult() ?: [];

        // 3. Agrupa por Atleta (para não repetir o nome se ele jogar em 2 categorias)
        $agrupado = [];
        foreach ($inscricoes as $i) {
            $uid = $i['adms_user_id'];
            if (!isset($agrupado[$uid])) {
                $agrupado[$uid] = [
                    'user_id' => $uid,
                    'atleta' => $i['atleta'],
                    'telefone_limpo' => preg_replace('/[^0-9]/', '', $i['telefone']), // Limpa para o link do WhatsApp
                    'telefone_display' => $i['telefone'],
                    'status_pagamento_id' => $i['status_pagamento_id'],
                    'categorias' => []
                ];
            }
            $agrupado[$uid]['categorias'][] = $i['categoria'];
        }

        // 4. Calcula o total a pagar e formata o array final
        foreach ($agrupado as $k => $a) {
            $qtdCategorias = count($a['categorias']);
            $total = ($qtdCategorias == 1) ? $valUma : (($qtdCategorias == 2) ? $valDuas : 0);
            $agrupado[$k]['valor_total'] = $total;
            $agrupado[$k]['categorias_str'] = implode(' <br> ', $a['categorias']);
        }

        // 5. Ordena: Quem falta pagar (1) aparece em cima, depois por ordem alfabética
        usort($agrupado, function($a, $b) {
            if ($a['status_pagamento_id'] == $b['status_pagamento_id']) {
                return strcmp($a['atleta'], $b['atleta']);
            }
            return $a['status_pagamento_id'] - $b['status_pagamento_id'];
        });

        $this->resultBd = $agrupado;
    }

    // Altera o status do pagamento no Banco de Dados
    public function alterarStatusPagamento(int $userId, int $compId, int $novoStatus): void
    {
        // 1. Garante que o usuário tem permissão para alterar (Dono do Clube ou SuperAdmin)
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

        // ====================================================================================
        // DOCAN FIX BLINDADO: Pega os IDs exatos das inscrições na tabela para evitar o bug do UPDATE Duplo
        // ====================================================================================
        $read->fullRead("SELECT id FROM adms_inscricoes WHERE adms_competicao_id = :comp AND adms_user_id = :user", "comp={$compId}&user={$userId}");
        $inscricoesDoAtleta = $read->getResult();

        if ($inscricoesDoAtleta) {
            $dados = ['status_pagamento_id' => $novoStatus];
            $up = new \App\adms\Models\helper\AdmsUpdate();

            $sucesso = false;
            foreach ($inscricoesDoAtleta as $ins) {
                // Atualiza de forma cirúrgica, usando apenas o ID primário!
                $up->exeUpdate("adms_inscricoes", $dados, "WHERE id = :id", "id={$ins['id']}");
                if ($up->getResult()) {
                    $sucesso = true;
                }
            }

            if ($sucesso) {
                $_SESSION['msg'] = "<p class='alert-success'>Status de pagamento atualizado com sucesso!</p>";
                $this->result = true;

                // ====================================================================================
                // DOCAN FIX: FILIAÇÃO AUTOMÁTICA DO ATLETA AO CLUBE
                // Executa a verificação e o cadastro apenas se o pagamento for Aprovado (Status = 2)
                // ====================================================================================
                if ($novoStatus == 2) {
                    $readFiliacao = new \App\adms\Models\helper\AdmsRead();
                    $readFiliacao->fullRead(
                        "SELECT id FROM adms_atleta_clube WHERE adms_user_id = :user_id AND empresa_id = :empresa_id LIMIT 1",
                        "user_id={$userId}&empresa_id={$empresaId}"
                    );

                    // Se a consulta voltar vazia (o atleta não é filiado a este clube), insere o registo
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
                $this->result = true; // Força sucesso para não trancar a tela
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Inscrição não encontrada no banco de dados.</p>";
            $this->result = false;
        }
    }
}