<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditCategoria
{
    private array|null $resultBd;
    private bool $result = false;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewCategoria(int $id): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        $view->fullRead("SELECT * FROM adms_categorias WHERE id=:id AND empresa_id=:empresa LIMIT 1", 
                        "id={$id}&empresa={$_SESSION['emp_user']}");
        $this->resultBd = $view->getResult();
    }

    public function update(array $data): void
    {
        $data['idade_minima'] = ($data['idade_minima'] !== '') ? (int)$data['idade_minima'] : null;
        $data['idade_maxima'] = ($data['idade_maxima'] !== '') ? (int)$data['idade_maxima'] : null;
        $data['pontuacao_minima'] = ($data['pontuacao_minima'] !== '') ? (int)$data['pontuacao_minima'] : null;
        $data['pontuacao_maxima'] = ($data['pontuacao_maxima'] !== '') ? (int)$data['pontuacao_maxima'] : null;
        $data['modified'] = date("Y-m-d H:i:s");

        $upCat = new \App\adms\Models\helper\AdmsUpdate();
        $upCat->exeUpdate("adms_categorias", $data, "WHERE id=:id AND empresa_id=:empresa", "id={$data['id']}&empresa={$_SESSION['emp_user']}");

        if ($upCat->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Categoria atualizada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Categoria não atualizada.</p>";
            $this->result = false;
        }
    }
}