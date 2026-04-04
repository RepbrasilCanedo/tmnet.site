<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar o equipamento no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteProd
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
     * Metodo recebe como parametro o ID que será usado para excluir o registro da tabela adms_users
     * Chama a função viewUser para verificar se o usuário esta cadastrado no sistema e na sequencia chama a função deleteImg para apagar a imagem do usuário
     * @param integer $id
     * @return void
     */
    public function deleteProd(int $id): void
    {
        $this->id = (int) $id;

        if($this->viewProd()){
            $deleteProd = new \App\adms\Models\helper\AdmsDelete();
            $deleteProd->exeDelete("adms_produtos", "WHERE id=:id", "id={$this->id}");
    
            if ($deleteProd->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Equipamento apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não apagado com sucesso!</p>";
                $this->result = false;
            }
        }else{
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa para verificar se o equipamento esta cadastrado no sistema, o resultado é enviado para a função deleteProd
     *
     * @return boolean
     */
    private function viewProd(): bool
    {
        $viewProd = new \App\adms\Models\helper\AdmsRead();
        $viewProd->fullRead("SELECT id FROM adms_produtos WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewProd->getResult();
        
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não encontrado!</p>";
            return false;
        }
    }
}
