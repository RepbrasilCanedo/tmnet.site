<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar cor no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteEmpresas
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
     * Chama as funções viewSit e checkColorUsed para fazer a confirmação do registro antes de excluir
     * @param integer $id
     * @return void
     */
    public function deleteEmpresas(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewEmpresas()) and ($this->checkEmpresasUsed())) {
            $deleteEmpresas = new \App\adms\Models\helper\AdmsDelete();
            $deleteEmpresas->exeDelete("adms_empresa", "WHERE id =:id", "id={$this->id}");

            if ($deleteEmpresas->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Empresa apagada com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Empresa não apagada com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se a cor esta cadastrada na tabela e envia o resultado para a função deleteColor
     * @return boolean
     */
    private function viewEmpresas(): bool
    {

        $viewEmpresas = new \App\adms\Models\helper\AdmsRead();
        $viewEmpresas->fullRead(
            "SELECT id FROM adms_empresa WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewEmpresas->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Empresa não encontrada!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem situação cadastrados usando a empresa a ser excluida, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteEmpresas
     * @return boolean
     */
    private function checkEmpresasUsed(): bool
    {
        $viewEmpresasUsed = new \App\adms\Models\helper\AdmsRead();
        $viewEmpresasUsed->fullRead("SELECT id FROM adms_empresa_unid WHERE empresa =:empresa LIMIT :limit", "empresa={$this->id}&limit=1");
        if ($viewEmpresasUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Empresa não pode ser apagada, há setor cadastrado para essa empresa!</p>";
            return false;
        } else {
            return true;
        }
    }
}
