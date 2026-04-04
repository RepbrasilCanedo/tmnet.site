<?php
namespace App\adms\Models;
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die("Erro: Página não encontrada<br>"); }

class AdmsAltStatusInscricao
{
    private bool $result = false;
    function getResult(): bool { return $this->result; }

    public function altStatus(int $id): void
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT status_inscricao FROM adms_competicoes WHERE id = :id AND empresa_id = :empresa LIMIT 1", "id={$id}&empresa={$_SESSION['emp_user']}");

        if ($read->getResult()) {
            // Força a conversão para inteiro para evitar falhas de tipagem
            $statusAtual = (int)$read->getResult()[0]['status_inscricao'];
            
            // Inverte o status: se for 1 vira 0, se for 0 vira 1
            $novoStatus = ($statusAtual == 1) ? 0 : 1; 

            $dataUpdate = ['status_inscricao' => $novoStatus, 'modified' => date("Y-m-d H:i:s")];
            $upComp = new \App\adms\Models\helper\AdmsUpdate();
            $upComp->exeUpdate("adms_competicoes", $dataUpdate, "WHERE id = :id", "id={$id}");

            if ($upComp->getResult()) {
                $statusMsg = ($novoStatus == 1) ? "Abertas" : "Encerradas";
                $_SESSION['msg'] = "<p class='alert-success'>Inscrições <b>{$statusMsg}</b> com sucesso!</p>";
                $this->result = true;
            } else {
                // ADICIONADO: Se o banco de dados falhar, ele agora avisa na tela!
                $_SESSION['msg'] = "<p class='alert-danger'>Erro interno: Falha ao tentar atualizar o banco de dados.</p>";
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada ou sem permissão.</p>";
        }
    }
}