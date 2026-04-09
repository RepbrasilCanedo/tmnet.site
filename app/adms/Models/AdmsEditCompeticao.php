<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditCompeticao
{
    private array|null $result;
    private bool $status = false;

    public function getResult(): array|null { return $this->result; }
    public function getStatus(): bool { return $this->status; }

    public function viewCompeticao(int $id): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        $view->fullRead("SELECT * FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", "id={$id}&empresa={$_SESSION['emp_user']}");
        $this->result = $view->getResult();
    }

    public function listarCategorias(): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, nome, pontuacao_maxima FROM adms_categorias WHERE empresa_id = :empresa ORDER BY pontuacao_maxima DESC, nome ASC", "empresa={$_SESSION['emp_user']}");
        return $read->getResult();
    }

    public function update(array $dados): void
    {
        $id = (int)$dados['id'];
        unset($dados['id'], $dados['SendEditComp']);

        if (isset($dados['categorias_selecionadas'])) {
            $dados['categorias_selecionadas'] = implode(',', $dados['categorias_selecionadas']);
        } else {
            $dados['categorias_selecionadas'] = null;
        }

        // DOCAN FIX: Força os campos de pontuação vazios a virarem ZERO na edição.
        $camposPontuacao = ['pts_campeao', 'pts_vice', 'pts_terceiro', 'pts_quartas', 'pts_vitoria_jogo', 'pts_derrota_jogo', 'pts_participacao'];
        foreach($camposPontuacao as $cp){
            $dados[$cp] = empty($dados[$cp]) ? 0 : (int)$dados[$cp];
        }

        $dados['modified'] = date("Y-m-d H:i:s");

        $up = new \App\adms\Models\helper\AdmsUpdate();
        $up->exeUpdate("adms_competicoes", $dados, "WHERE id = :id AND empresa_id = :empresa", "id={$id}&empresa={$_SESSION['emp_user']}");

        if ($up->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso: Competição atualizada com sucesso!</p>";
            $this->status = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível atualizar a competição ou não houve alterações.</p>";
            $this->status = false;
        }
    }
}