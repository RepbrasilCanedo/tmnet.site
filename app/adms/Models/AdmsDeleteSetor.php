<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar Setor no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteSetor
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Metodo recebe como parametro o ID do registro que será excluido
     * @return void
     */
    public function deleteSetor(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewSetor())) {
            $deleteSetor = new \App\adms\Models\helper\AdmsDelete();
            $deleteSetor->exeDelete("adms_empresa_unid", "WHERE id =:id", "id={$this->id}");

            if ($deleteSetor->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Setor apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor não apagado com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se o setor esta cadastrada na tabela e envia o resultado para a função deleteSetor
     * @return boolean
     */
    private function viewSetor(): bool
    {

        $viewSetor = new \App\adms\Models\helper\AdmsRead();
        $viewSetor->fullRead(
            "SELECT id FROM adms_empresa_unid WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewSetor->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor não encontrada!</p>";
            return false;
        }
    }
}
