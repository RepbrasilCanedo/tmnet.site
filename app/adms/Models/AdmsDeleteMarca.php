<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar marca no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteMarca
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
     * Chama as funções viewSit e checkMarcaUsed para fazer a confirmação do registro antes de excluir
     * @param integer $id
     * @return void
     */
    public function deleteMarca(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewMarca()) and ($this->checkMarcaUsed())) {
            $deleteMarca = new \App\adms\Models\helper\AdmsDelete();
            $deleteMarca->exeDelete("adms_marca", "WHERE id =:id", "id={$this->id}");

            if ($deleteMarca->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Marca apagada com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Marca não apagada com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se a cor esta cadastrada na tabela e envia o resultado para a função deleteMarca
     * @return boolean
     */
    private function viewMarca(): bool
    {

        $viewMarca = new \App\adms\Models\helper\AdmsRead();
        $viewMarca->fullRead(
            "SELECT id FROM adms_marca WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewMarca->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Marca não encontrada!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem situação cadastrados usando a cor a ser excluida, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteMarca
     * @return boolean
     */
    private function checkMarcaUsed(): bool
    {
        $viewMarcaUsed = new \App\adms\Models\helper\AdmsRead();
        $viewMarcaUsed->fullRead("SELECT id FROM adms_equipamentos WHERE marca_id  =:marca_id  LIMIT :limit", "marca_id ={$this->id}&limit=1");
        if ($viewMarcaUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Marca não pode ser apagada, há equipamentos cadastrados com essa Marca
            !</p>";
            return false;
        } else {
            return true;
        }
    }
}
