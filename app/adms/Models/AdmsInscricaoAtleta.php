<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsInscricaoAtleta
{
    private bool $result = false;
    private array|null $resultBd;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function listarTorneios(int $userId, ?int $torneioId = null): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT data_nascimento, pontuacao_ranking FROM adms_users WHERE id = :id LIMIT 1", "id={$userId}");
        $atleta = $read->getResult()[0] ?? null;

        $campos = "c.id, c.nome_torneio, c.data_evento, c.local_evento, c.categoria_cbtm, c.fator_multiplicador, 
                   c.categorias_selecionadas, c.valor_uma_categoria, c.valor_duas_categorias, c.chave_pix,
                   emp.nome_fantasia as clube_nome, emp.logo as clube_logo, emp.id as clube_id";

        if ($torneioId) {
            $read->fullRead(
                "SELECT $campos FROM adms_competicoes c
                 LEFT JOIN adms_emp_principal emp ON emp.id = c.empresa_id
                 WHERE c.status_inscricao = 1 AND c.id = :torneio_id
                 ORDER BY c.data_evento ASC",
                "torneio_id={$torneioId}"
            );
        } else {
            $read->fullRead(
                "SELECT $campos FROM adms_competicoes c
                 LEFT JOIN adms_emp_principal emp ON emp.id = c.empresa_id
                 WHERE c.status_inscricao = 1 ORDER BY c.data_evento ASC"
            );
        }
        
        $torneios = $read->getResult() ?: [];

        $read->fullRead(
            "SELECT adms_competicao_id, adms_categoria_id, status_pagamento_id 
             FROM adms_inscricoes WHERE adms_user_id = :user_id", 
            "user_id={$userId}"
        );
        $minhasInscricoes = $read->getResult() ?: [];

        $inscricoesPorTorneio = [];
        $statusPorTorneio = [];
        
        foreach ($minhasInscricoes as $insc) {
            $compId = $insc['adms_competicao_id'];
            if (!isset($inscricoesPorTorneio[$compId])) {
                $inscricoesPorTorneio[$compId] = [];
            }
            $inscricoesPorTorneio[$compId][] = $insc['adms_categoria_id'];
            $statusPorTorneio[$compId] = $insc['status_pagamento_id'] ?? 1; 
        }

        $ratingAtleta = (float)($atleta['pontuacao_ranking'] ?? 0);

        foreach ($torneios as $key => $t) {
            $torneios[$key]['categorias_inscritas'] = isset($inscricoesPorTorneio[$t['id']]) ? implode(',', $inscricoesPorTorneio[$t['id']]) : '';
            $torneios[$key]['status_pagamento'] = $statusPorTorneio[$t['id']] ?? 1;

            $idadeAtleta = 0;
            // DOCAN FIX: Proteção contra data '0000-00-00'
            if (!empty($atleta['data_nascimento']) && $atleta['data_nascimento'] !== '0000-00-00') {
                $nascimento = new \DateTime($atleta['data_nascimento']);
                $dataEvento = new \DateTime($t['data_evento']);
                $idadeAtleta = $nascimento->diff($dataEvento)->y;
            }

            // Pega as categorias selecionadas e limpa qualquer lixo
            $stringCats = (string)($t['categorias_selecionadas'] ?? '');
            $stringCats = preg_replace('/[^0-9,]/', '', $stringCats); 
            $catIdsArray = array_filter(explode(',', $stringCats));
            
            $torneios[$key]['tem_categorias_configuradas'] = !empty($catIdsArray);
            $elegiveis = [];

            // ====================================================================
            // DOCAN FIX BLINDADO: A busca é feita DIRETAMENTE pelo ID via Banco de Dados!
            // ====================================================================
            if (!empty($catIdsArray)) {
                $idsCsv = implode(',', $catIdsArray); // Ex: '20,21'
                $readCat = new \App\adms\Models\helper\AdmsRead();
                
                // Pede ao banco APENAS as categorias deste torneio específico
                $readCat->fullRead("SELECT * FROM adms_categorias WHERE id IN ({$idsCsv})");
                $catsDoTorneio = $readCat->getResult() ?: [];

                foreach ($catsDoTorneio as $cat) {
                    $apto = true;

                    // Converte NULLs e textos vazios para Zero
                    $idadeMin = (isset($cat['idade_minima']) && is_numeric($cat['idade_minima'])) ? (int)$cat['idade_minima'] : 0;
                    $idadeMax = (isset($cat['idade_maxima']) && is_numeric($cat['idade_maxima'])) ? (int)$cat['idade_maxima'] : 0;
                    $ratingMax = (isset($cat['pontuacao_maxima']) && is_numeric($cat['pontuacao_maxima'])) ? (float)$cat['pontuacao_maxima'] : 0;

                    // Só barra se o valor mínimo for estritamente MAIOR que zero
                    if ($idadeMin > 0 && $idadeAtleta < $idadeMin) $apto = false;
                    if ($idadeMax > 0 && $idadeAtleta > $idadeMax) $apto = false;
                    if ($ratingMax > 0 && $ratingAtleta > $ratingMax) $apto = false;

                    if ($apto) {
                        $elegiveis[] = $cat;
                    }
                }
            }
            
            $torneios[$key]['categorias_elegiveis'] = $elegiveis;
        }

        $this->resultBd = $torneios;
    }

    public function inscrever(array $dados): void
    {
        $compId = (int)$dados['competicao_id'];
        $userId = (int)$_SESSION['user_id']; 

        if (empty($dados['categorias_selecionadas'])) {
            $_SESSION['msg'] = "<p class='alert-warning'>Você deve escolher pelo menos uma categoria/divisão!</p>";
            $this->result = false;
            return;
        }

        if (count($dados['categorias_selecionadas']) > 2) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro de Regulamento: O limite é de apenas 2 categorias por torneio!</p>";
            $this->result = false;
            return;
        }

        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, data_evento, status_inscricao FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $compInfo = $read->getResult()[0] ?? null;
        
        if (!$compInfo || $compInfo['status_inscricao'] != 1) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: As inscrições para este torneio foram encerradas!</p>";
            $this->result = false;
            return;
        }

        $read->fullRead("SELECT data_nascimento, pontuacao_ranking FROM adms_users WHERE id = :id LIMIT 1", "id={$userId}");
        $atleta = $read->getResult()[0];

        $idade = 0;
        if (!empty($atleta['data_nascimento']) && $atleta['data_nascimento'] !== '0000-00-00') {
            $nasc = new \DateTime($atleta['data_nascimento']);
            $evt = new \DateTime($compInfo['data_evento']);
            $idade = $nasc->diff($evt)->y;
        }
        $rating = (float)($atleta['pontuacao_ranking'] ?? 0);

        $catsStr = implode(',', array_map('intval', $dados['categorias_selecionadas']));
        $read->fullRead("SELECT * FROM adms_categorias WHERE id IN ($catsStr)");
        $categoriasMarcadas = $read->getResult() ?: [];
        
        foreach ($categoriasMarcadas as $cat) {
            $idadeMin = (isset($cat['idade_minima']) && is_numeric($cat['idade_minima'])) ? (int)$cat['idade_minima'] : 0;
            $idadeMax = (isset($cat['idade_maxima']) && is_numeric($cat['idade_maxima'])) ? (int)$cat['idade_maxima'] : 0;
            $ratingMax = (isset($cat['pontuacao_maxima']) && is_numeric($cat['pontuacao_maxima'])) ? (float)$cat['pontuacao_maxima'] : 0;

            if ($idadeMin > 0 && $idade < $idadeMin) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Idade insuficiente para a categoria {$cat['nome']}.</p>";
                $this->result = false; return;
            }
            if ($idadeMax > 0 && $idade > $idadeMax) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Idade excede o limite da categoria {$cat['nome']}.</p>";
                $this->result = false; return;
            }
            if ($ratingMax > 0 && $rating > $ratingMax) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Seus pontos excedem o limite da categoria {$cat['nome']}.</p>";
                $this->result = false; return;
            }
        }

        $read->fullRead("SELECT status_pagamento_id FROM adms_inscricoes WHERE adms_competicao_id = :comp_id AND adms_user_id = :user_id LIMIT 1", "comp_id={$compId}&user_id={$userId}");
        $statusAtual = $read->getResult()[0]['status_pagamento_id'] ?? 1;

        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_inscricoes", "WHERE adms_competicao_id = :comp_id AND adms_user_id = :user_id", "comp_id={$compId}&user_id={$userId}");

        $create = new \App\adms\Models\helper\AdmsCreate();
        $qtdInscricoes = 0;

        foreach ($dados['categorias_selecionadas'] as $catId) {
            $dadosInscricao = [
                'adms_competicao_id' => $compId,
                'adms_user_id' => $userId,
                'adms_categoria_id' => (int)$catId,
                'status_pagamento_id' => $statusAtual,
                'created' => date("Y-m-d H:i:s")
            ];
            $create->exeCreate("adms_inscricoes", $dadosInscricao);
            $qtdInscricoes++;
        }

        $_SESSION['msg'] = "<p class='alert-success'>🚀 Sucesso! Inscrição confirmada em {$qtdInscricoes} categoria(s).</p>";
        $this->result = true;
    }

    public function cancelarInscricao(array $dados): void
    {
        $compId = (int)$dados['competicao_id'];
        $userId = (int)$_SESSION['user_id'];
        
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_inscricoes", "WHERE adms_competicao_id = :comp_id AND adms_user_id = :user_id", "comp_id={$compId}&user_id={$userId}");
        
        if ($delete->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Inscrição cancelada com sucesso.</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ao cancelar a inscrição.</p>";
            $this->result = false;
        }
    }
}