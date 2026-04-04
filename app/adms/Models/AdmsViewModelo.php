<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar modelo no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewModelo
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo para visualizar os detalhes do modelo
     * Recebe o ID do modelo que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro.
     * @param integer $id
     * @return void
     */
    public function viewModelo(int $id): void
    {
        $this->id = $id;

        $viewModelo = new \App\adms\Models\helper\AdmsRead();
        $viewModelo->fullRead("SELECT id, name, created, modified FROM adms_model WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewModelo->getResult();        
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não encontrada!</p>";
            $this->result = false;
        }
    }
}
