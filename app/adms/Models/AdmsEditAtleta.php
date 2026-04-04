<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditAtleta
{
    private array|null $result;
    private bool $resultUpdate;

    function getResult(): array|null|bool { return $this->result; }

    public function viewAtleta(int $id): void
    {
        $viewAtleta = new \App\adms\Models\helper\AdmsRead();
        $viewAtleta->fullRead("SELECT * FROM adms_atletas WHERE id=:id LIMIT :limit", "id={$id}&limit=1");
        $this->result = $viewAtleta->getResult();
    }

    public function update(array $data): void
    {
        $this->result = $data;
        $id = $this->result['id'];
        unset($this->result['id']);
        
        $this->result['modified'] = date("Y-m-d H:i:s");

        $upAtleta = new \App\adms\Models\helper\AdmsUpdate();
        $upAtleta->exeUpdate("adms_atletas", $this->result, "WHERE id=:id", "id={$id}");

        if ($upAtleta->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Atleta atualizado com sucesso!</p>";
            $this->resultUpdate = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não foi atualizado.</p>";
            $this->resultUpdate = false;
        }
    }
}