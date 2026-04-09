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

    public function listarTorneios(int $userId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT data_nascimento, pontuacao_ranking FROM adms_users WHERE id = :id LIMIT 1", "id={$userId}");
        $atleta = $read->getResult()[0] ?? null;

        $read->fullRead(
            "SELECT id, nome_torneio, data_evento, local_evento, categoria_cbtm, fator_multiplicador, categorias_selecionadas
             FROM adms_competicoes
             WHERE empresa_id = :empresa AND status_inscricao = 1
             ORDER BY data_evento ASC",
            "empresa={$_SESSION['emp_user']}"
        );
        
        $torneios = $read->getResult() ?: [];

        $read->fullRead(
            "SELECT adms_competicao_id, adms_categoria_id 
             FROM adms_inscricoes 
             WHERE adms_user_id = :user_id",
            "user_id={$userId}"
        );
        $minhasInscricoes = $read->getResult() ?: [];

        $inscricoesPorTorneio = [];
        foreach ($minhasInscricoes as $insc) {
            $compId = $insc['adms_competicao_id'];
            if (!isset($inscricoesPorTorneio[$compId])) {
                $inscricoesPorTorneio[$compId] = [];
            }
            $inscricoesPorTorneio[$compId][] = $insc['adms_categoria_id'];
        }

        $read->fullRead("SELECT id, nome, idade_minima, idade_maxima, pontuacao_maxima FROM adms_categorias WHERE empresa_id = :empresa", "empresa={$_SESSION['emp_user']}");
        $todasCategorias = $read->getResult() ?: [];

        $ratingAtleta = (float)($atleta['pontuacao_ranking'] ?? 0);

        foreach ($torneios as $key => $t) {
            
            $torneios[$key]['categorias_inscritas'] = isset($inscricoesPorTorneio[$t['id']]) ? implode(',', $inscricoesPorTorneio[$t['id']]) : '';

            $idadeAtleta = 0;
            if (!empty($atleta['data_nascimento'])) {
                $nascimento = new \DateTime($atleta['data_nascimento']);
                $dataEvento = new \DateTime($t['data_evento']);
                $idadeAtleta = $nascimento->diff($dataEvento)->y;
            }

            $catIdsTorneio = explode(',', $t['categorias_selecionadas'] ?? '');
            $elegiveis = [];

            foreach ($todasCategorias as $cat) {
                if (in_array((string)$cat['id'], $catIdsTorneio)) {
                    $apto = true;

                    $idadeMin = (is_numeric($cat['idade_minima']) && $cat['idade_minima'] > 0) ? (int)$cat['idade_minima'] : null;
                    $idadeMax = (is_numeric($cat['idade_maxima']) && $cat['idade_maxima'] > 0) ? (int)$cat['idade_maxima'] : null;
                    $ratingMax = (is_numeric($cat['pontuacao_maxima']) && $cat['pontuacao_maxima'] > 0) ? (float)$cat['pontuacao_maxima'] : null;

                    if ($idadeMin !== null && $idadeAtleta < $idadeMin) $apto = false;
                    if ($idadeMax !== null && $idadeAtleta > $idadeMax) $apto = false;
                    if ($ratingMax !== null && $ratingAtleta > $ratingMax) $apto = false;

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

        // ==============================================================
        // DOCAN FIX: TRAVA BACK-END DE LIMITE DE INSCRIÇÕES (MÁX: 2)
        // ==============================================================
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
        if (!empty($atleta['data_nascimento'])) {
            $nasc = new \DateTime($atleta['data_nascimento']);
            $evt = new \DateTime($compInfo['data_evento']);
            $idade = $nasc->diff($evt)->y;
        }
        $rating = (float)($atleta['pontuacao_ranking'] ?? 0);

        $catsStr = implode(',', array_map('intval', $dados['categorias_selecionadas']));
        $read->fullRead("SELECT nome, idade_minima, idade_maxima, pontuacao_maxima FROM adms_categorias WHERE id IN ($catsStr)");
        $categoriasMarcadas = $read->getResult() ?: [];
        
        foreach ($categoriasMarcadas as $cat) {
            $idadeMin = (is_numeric($cat['idade_minima']) && $cat['idade_minima'] > 0) ? (int)$cat['idade_minima'] : null;
            $idadeMax = (is_numeric($cat['idade_maxima']) && $cat['idade_maxima'] > 0) ? (int)$cat['idade_maxima'] : null;
            $ratingMax = (is_numeric($cat['pontuacao_maxima']) && $cat['pontuacao_maxima'] > 0) ? (float)$cat['pontuacao_maxima'] : null;

            if ($idadeMin !== null && $idade < $idadeMin) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Idade insuficiente para a categoria {$cat['nome']} (Mínimo: {$idadeMin} anos).</p>";
                $this->result = false; return;
            }
            if ($idadeMax !== null && $idade > $idadeMax) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Idade excede o limite da categoria {$cat['nome']} (Máximo: {$idadeMax} anos).</p>";
                $this->result = false; return;
            }
            if ($ratingMax !== null && $rating > $ratingMax) {
                $_SESSION['msg'] = "<p class='alert-danger'>Autenticidade: Seus pontos ({$rating}) excedem o limite da {$cat['nome']} ({$ratingMax} pts).</p>";
                $this->result = false; return;
            }
        }

        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_inscricoes", "WHERE adms_competicao_id = :comp_id AND adms_user_id = :user_id", "comp_id={$compId}&user_id={$userId}");

        $create = new \App\adms\Models\helper\AdmsCreate();
        $qtdInscricoes = 0;

        foreach ($dados['categorias_selecionadas'] as $catId) {
            $dadosInscricao = [
                'adms_competicao_id' => $compId,
                'adms_user_id' => $userId,
                'adms_categoria_id' => (int)$catId,
                'created' => date("Y-m-d H:i:s")
            ];
            $create->exeCreate("adms_inscricoes", $dadosInscricao);
            $qtdInscricoes++;
        }

        $_SESSION['msg'] = "<p class='alert-success'>Sucesso! Inscrição confirmada em {$qtdInscricoes} categoria(s). Prepare a sua raquete!</p>";
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