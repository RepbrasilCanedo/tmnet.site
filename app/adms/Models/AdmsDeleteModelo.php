<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar modelo no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteModelo
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
     * Chama as funções viewSit e checkModeloUsed para fazer a confirmação do registro antes de excluir
     * @param integer $id
     * @return void
     */
    public function deleteModelo(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewModelo()) and ($this->checkModeloUsed())) {
            $deleteModelo = new \App\adms\Models\helper\AdmsDelete();
            $deleteModelo->exeDelete("adms_model", "WHERE id =:id", "id={$this->id}");

            if ($deleteModelo->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Modelo apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não apagado com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se a cor esta cadastrada na tabela e envia o resultado para a função deleteModelo
     * @return boolean
     */
    private function viewModelo(): bool
    {

        $viewModelo = new \App\adms\Models\helper\AdmsRead();
        $viewModelo->fullRead(
            "SELECT id FROM adms_model WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewModelo->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não encontrado!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem situação cadastrados usando a cor a ser excluida, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteModelo
     * @return boolean
     */
    private function checkModeloUsed(): bool
    {
        $viewModeloUsed = new \App\adms\Models\helper\AdmsRead();
        $viewModeloUsed->fullRead("SELECT id FROM adms_equipamentos WHERE modelo_id   =:modelo_id   LIMIT :limit", "modelo_id  ={$this->id}&limit=1");
        if ($viewModeloUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não pode ser apagada, há equipamentos cadastrados com esse Modelo
            !</p>";
            return false;
        } else {
            return true;
        }
    }
}
