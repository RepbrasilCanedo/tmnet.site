<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddPartida
{
    private array|null $data;
    private bool $result;

    function getResult(): bool { return $this->result; }

    public function create(array $data): void
    {
        $this->data = $data;
        
        // 1. Identifica o vencedor baseado nos sets
        if ($this->data['sets_atleta_a'] > $this->data['sets_atleta_b']) {
            $this->data['vencedor_id'] = $this->data['atleta_a_id'];
        } else {
            $this->data['vencedor_id'] = $this->data['atleta_b_id'];
        }

        // 2. Busca o peso (fator_multiplicador) da competição
        $readComp = new \App\adms\Models\helper\AdmsRead();
        $readComp->fullRead("SELECT fator_multiplicador FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$this->data['adms_competicao_id']}");
        $resComp = $readComp->getResult();
        
        $fator = (isset($resComp[0]['fator_multiplicador'])) ? (float)$resComp[0]['fator_multiplicador'] : 1.0;

        // 3. Cálculo da pontuação CBTM: Base (10) * Fator do Torneio
        $this->data['pontos_ganhos'] = 10 * $fator;
        $this->data['created'] = date("Y-m-d H:i:s");

        // 4. Salva a partida
        $createPartida = new \App\adms\Models\helper\AdmsCreate();
        $createPartida->exeCreate("adms_partidas", $this->data);

        if ($createPartida->getResult()) {
            // 5. ATUALIZA O RANKING DO ATLETA VENCEDOR
            $this->updateAtletaRanking($this->data['vencedor_id'], (int)$this->data['pontos_ganhos']);
            
            $_SESSION['msg'] = "<p class='alert-success'>Resultado registrado! Pontos atribuídos: {$this->data['pontos_ganhos']} (Fator x{$fator})</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro ao registrar partida.</p>";
            $this->result = false;
        }
    }

    private function updateAtletaRanking(int $atleta_id, int $pontos): void
    {
        $readAtleta = new \App\adms\Models\helper\AdmsRead();
        $readAtleta->fullRead("SELECT pontuacao_ranking FROM adms_users WHERE id=:id LIMIT 1", "id={$atleta_id}");
        $res = $readAtleta->getResult();

        if ($res) {
            $novaPontuacao = $res[0]['pontuacao_ranking'] + $pontos;
            $dataUpdate['pontuacao_ranking'] = $novaPontuacao;
            $dataUpdate['modified'] = date("Y-m-d H:i:s");

            $upRanking = new \App\adms\Models\helper\AdmsUpdate();
            $upRanking->exeUpdate("adms_users", $dataUpdate, "WHERE id=:id", "id={$atleta_id}");
        }
    }

    public function listAtletas(): array|null
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id, name, apelido FROM adms_users WHERE empresa_id = :empresa AND adms_access_level_id = :nivel ORDER BY name ASC", "empresa={$_SESSION['emp_user']}&nivel=14");
        return $list->getResult();
    }
}