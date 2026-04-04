<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsDeletePartida
{
    private bool $result = false;
    private array|null $dadosPartida;
    private int $competicaoId;

    function getResult(): bool { return $this->result; }
    function getCompeticaoId(): int { return $this->competicaoId; }

    public function deletePartida(int $id): void
    {
        $viewPartida = new \App\adms\Models\helper\AdmsRead();
        
        // 1. Busca a partida garantindo que a competição pertence à empresa logada
        $viewPartida->fullRead(
            "SELECT p.id, p.vencedor_id, p.pontos_ganhos, p.adms_competicao_id 
             FROM adms_partidas p
             INNER JOIN adms_competicoes c ON c.id = p.adms_competicao_id
             WHERE p.id = :id AND c.empresa_id = :empresa LIMIT 1",
            "id={$id}&empresa={$_SESSION['emp_user']}"
        );

        if ($viewPartida->getResult()) {
            $this->dadosPartida = $viewPartida->getResult()[0];
            $this->competicaoId = $this->dadosPartida['adms_competicao_id'];
            
            // 2. Estorna os pontos do ranking do atleta
            $this->estornarPontos($this->dadosPartida['vencedor_id'], $this->dadosPartida['pontos_ganhos']);

            // 3. Apaga o registro da partida
            $delete = new \App\adms\Models\helper\AdmsDelete();
            $delete->exeDelete("adms_partidas", "WHERE id = :id", "id={$id}");

            if ($delete->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Partida excluída e pontos ({$this->dadosPartida['pontos_ganhos']}) estornados do ranking com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível excluir a partida do banco de dados.</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Partida não encontrada ou você não tem permissão para excluí-la.</p>";
            $this->result = false;
        }
    }

    private function estornarPontos(int $atletaId, int $pontosEstorno): void
    {
        $readAtleta = new \App\adms\Models\helper\AdmsRead();
        $readAtleta->fullRead("SELECT pontuacao_ranking FROM adms_users WHERE id=:id LIMIT 1", "id={$atletaId}");
        
        if ($readAtleta->getResult()) {
            $pontuacaoAtual = $readAtleta->getResult()[0]['pontuacao_ranking'];
            $novaPontuacao = $pontuacaoAtual - $pontosEstorno;
            
            // Garante que a pontuação não fique negativa caso haja algum ajuste manual prévio
            if ($novaPontuacao < 0) {
                $novaPontuacao = 0;
            }

            $dataUpdate['pontuacao_ranking'] = $novaPontuacao;
            $dataUpdate['modified'] = date("Y-m-d H:i:s");

            $upRanking = new \App\adms\Models\helper\AdmsUpdate();
            $upRanking->exeUpdate("adms_users", $dataUpdate, "WHERE id=:id", "id={$atletaId}");
        }
    }
}