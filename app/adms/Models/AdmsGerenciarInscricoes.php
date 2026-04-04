<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsGerenciarInscricoes
{
    private bool $result = false;
    private array|null $inscritos = null;
    private array|null $disponiveis = null;
    private array|null $categoriasTorneio = null;

    function getResult(): bool { return $this->result; }
    function getInscritos(): array|null { return $this->inscritos; }
    function getDisponiveis(): array|null { return $this->disponiveis; }
    function getCategoriasTorneio(): array|null { return $this->categoriasTorneio; }

    public function carregarListas(int $compId): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        
        $read->fullRead("SELECT categorias_selecionadas FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $catIdsStr = $read->getResult()[0]['categorias_selecionadas'] ?? '';
        
        $catIdsArray = explode(',', $catIdsStr);

        $read->fullRead("SELECT * FROM adms_categorias WHERE empresa_id = :empresa ORDER BY pontuacao_maxima DESC, nome ASC", "empresa={$_SESSION['emp_user']}");
        $todasCategorias = $read->getResult() ?: [];

        $this->categoriasTorneio = [];
        foreach ($todasCategorias as $cat) {
            if (in_array((string)$cat['id'], $catIdsArray)) {
                $this->categoriasTorneio[] = $cat;
            }
        }

        $read->fullRead(
            "SELECT i.id as inscricao_id, u.id as atleta_id, u.name, u.apelido, u.pontuacao_ranking, c.nome as nome_categoria 
             FROM adms_inscricoes i 
             INNER JOIN adms_users u ON u.id = i.adms_user_id 
             INNER JOIN adms_categorias c ON c.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id 
             ORDER BY c.nome ASC, u.pontuacao_ranking DESC", 
            "comp_id={$compId}"
        );
        $this->inscritos = $read->getResult();

        // CONTA AS INSCRIÇÕES E REMOVE QUEM JÁ TEM 2 (Regra de Ouro)
        $read->fullRead(
            "SELECT id, name, apelido, pontuacao_ranking, data_nascimento,
                    (SELECT COUNT(id) FROM adms_inscricoes WHERE adms_competicao_id = :comp_id AND adms_user_id = adms_users.id) AS qtd_inscricoes
             FROM adms_users 
             WHERE adms_access_level_id = 14 AND empresa_id = :empresa 
             ORDER BY name ASC", 
            "empresa={$_SESSION['emp_user']}&comp_id={$compId}"
        );
        
        $todosAtletas = $read->getResult() ?: [];
        $this->disponiveis = [];
        
        foreach ($todosAtletas as $atl) {
            if ($atl['qtd_inscricoes'] < 2) {
                $this->disponiveis[] = $atl; // Só entra na lista se tiver 0 ou 1 inscrição
            }
        }
    }

    public function inscreverAtletaManual(array $dados): void
    {
        if (empty($dados['adms_user_id'])) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Selecione um atleta!</p>";
            $this->result = false; return;
        }

        if (empty($dados['categorias_selecionadas'])) {
            $_SESSION['msg'] = "<p class='alert-warning'>Erro: Selecione pelo menos uma categoria para este atleta!</p>";
            $this->result = false; return;
        }

        $compId = (int)$dados['adms_competicao_id'];
        $userId = (int)$dados['adms_user_id'];
        $categoriasSelecionadas = $dados['categorias_selecionadas'];

        $read = new \App\adms\Models\helper\AdmsRead();

        // REGRA OFICIAL: Máximo de 2 inscrições por torneio
        $read->fullRead("SELECT COUNT(id) as total FROM adms_inscricoes WHERE adms_competicao_id = :comp AND adms_user_id = :user", "comp={$compId}&user={$userId}");
        $qtdExistente = (int)($read->getResult()[0]['total'] ?? 0);
        $qtdNovas = count($categoriasSelecionadas);

        if (($qtdExistente + $qtdNovas) > 2) {
            $vagasRestantes = 2 - $qtdExistente;
            $_SESSION['msg'] = "<p class='alert-danger'>Erro de Regulamento: O limite é de 2 categorias por atleta. Este atleta tem direito a apenas mais {$vagasRestantes} vaga(s) neste torneio.</p>";
            $this->result = false; return;
        }

        $read->fullRead("SELECT data_nascimento, pontuacao_ranking FROM adms_users WHERE id = :id LIMIT 1", "id={$userId}");
        $atleta = $read->getResult()[0];

        $read->fullRead("SELECT data_evento FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        $compInfo = $read->getResult()[0];

        $idade = 0;
        if (!empty($atleta['data_nascimento'])) {
            $nascimento = new \DateTime($atleta['data_nascimento']);
            $dataEvento = new \DateTime($compInfo['data_evento']);
            $idade = $nascimento->diff($dataEvento)->y;
        }
        $rating = (float)$atleta['pontuacao_ranking'];

        $create = new \App\adms\Models\helper\AdmsCreate();
        $qtd = 0;

        foreach ($categoriasSelecionadas as $catId) {
            $catId = (int)$catId;

            $read->fullRead("SELECT id FROM adms_inscricoes WHERE adms_competicao_id = :comp AND adms_user_id = :user AND adms_categoria_id = :cat LIMIT 1", 
                            "comp={$compId}&user={$userId}&cat={$catId}");
            if ($read->getResult()) {
                continue; 
            }

            $read->fullRead("SELECT nome, idade_minima, idade_maxima, pontuacao_maxima FROM adms_categorias WHERE id = :id LIMIT 1", "id={$catId}");
            $categoria = $read->getResult()[0];

            if (!is_null($categoria['idade_minima']) && $categoria['idade_minima'] !== '' && $idade < $categoria['idade_minima']) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: O atleta tem {$idade} anos. A '{$categoria['nome']}' exige mínimo de {$categoria['idade_minima']}.</p>";
                $this->result = false; return;
            }
            if (!is_null($categoria['idade_maxima']) && $categoria['idade_maxima'] !== '' && $idade > $categoria['idade_maxima']) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: O atleta tem {$idade} anos. A '{$categoria['nome']}' permite máximo de {$categoria['idade_maxima']}.</p>";
                $this->result = false; return;
            }
            if (!is_null($categoria['pontuacao_maxima']) && $categoria['pontuacao_maxima'] !== '' && $rating > $categoria['pontuacao_maxima']) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro de Nível: O atleta tem {$rating} pts. Ele não pode descer para a '{$categoria['nome']}' (Máximo permitido: {$categoria['pontuacao_maxima']} pts).</p>";
                $this->result = false; return;
            }

            $dadosInscricao = [
                'adms_competicao_id' => $compId,
                'adms_user_id' => $userId,
                'adms_categoria_id' => $catId,
                'created' => date("Y-m-d H:i:s")
            ];
            
            $create->exeCreate("adms_inscricoes", $dadosInscricao);
            
            if ($create->getResult()) {
                $qtd++;
            }
        }

        if ($qtd > 0) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso! Atleta inscrito manualmente em {$qtd} categoria(s).</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ou Inscrição Duplicada. Verifique os dados.</p>";
            $this->result = false;
        }
    }

    public function removerInscricaoManual(int $inscricaoId): void
    {
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_inscricoes", "WHERE id = :id", "id={$inscricaoId}");

        if ($delete->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Inscrição removida com sucesso!</p>";
            $this->result = true;
        }
    }
}