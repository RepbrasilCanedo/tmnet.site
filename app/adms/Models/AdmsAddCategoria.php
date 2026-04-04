<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddCategoria
{
    private array|null $data;
    private bool $result;

    function getResult(): bool
    {
        return $this->result;
    }

    public function create(array $data): void
    {
        $this->data = $data;

        // Trata campos vazios para NULL no banco (preservando o 0 se for digitado)
        $this->data['idade_minima'] = ($this->data['idade_minima'] !== '') ? (int)$this->data['idade_minima'] : null;
        $this->data['idade_maxima'] = ($this->data['idade_maxima'] !== '') ? (int)$this->data['idade_maxima'] : null;
        
        $this->data['pontuacao_minima'] = ($this->data['pontuacao_minima'] !== '') ? (int)$this->data['pontuacao_minima'] : null;
        $this->data['pontuacao_maxima'] = ($this->data['pontuacao_maxima'] !== '') ? (int)$this->data['pontuacao_maxima'] : null;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        
        // Valida apenas o nome, pois as idades e pontuações podem ser nulas (Ex: Categoria Livre/Absoluto)
        $dataToValidate = ['nome' => $this->data['nome']];
        $valEmptyField->valField($dataToValidate);

        if ($valEmptyField->getResult()) {
            $this->add();
        } else {
            $this->result = false;
        }
    }

    private function add(): void
    {
        // Força a categoria a pertencer ao clube (empresa) logado
        $this->data['empresa_id'] = $_SESSION['emp_user'];
        $this->data['created'] = date("Y-m-d H:i:s");

        $createCat = new \App\adms\Models\helper\AdmsCreate();
        $createCat->exeCreate("adms_categorias", $this->data);

        if ($createCat->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso: Categoria criada corretamente!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível criar a categoria.</p>";
            $this->result = false;
        }
    }
}