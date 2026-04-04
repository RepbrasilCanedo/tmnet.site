<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddAtletas
{
    private array|null $data;
    private bool $result;

    function getResult(): bool
    {
        return $this->result;
    }

    public function createAtleta(array $data): void
    {
        $this->data = $data;
        
        // Limpeza básica de tags
        $this->data = array_map('strip_tags', $this->data);
        $this->data = array_map('trim', $this->data);

        if ($this->data['nome'] != "") {
            $this->data['empresa_id'] = $_SESSION['emp_user'] ?? $_SESSION['user_id'];
            $this->data['created'] = date("Y-m-d H:i:s");
            
            $createAtleta = new \App\adms\Models\helper\AdmsCreate();
            $createAtleta->exeCreate("adms_atletas", $this->data);

            if ($createAtleta->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Atleta cadastrado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Atleta não cadastrado.</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: O campo nome é obrigatório.</p>";
            $this->result = false;
        }
    }
}