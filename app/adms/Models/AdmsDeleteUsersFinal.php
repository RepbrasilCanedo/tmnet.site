<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar o usuário no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteUsersFinal
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var string $delDirectory Recebe o endereço para apagar o diretório */
    private string $delDirectory;

    /** @var string $delImg Recebe o endereço para apagar a imagem */
    private string $delImg;

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
    public function deleteUser(int $id): void
    {
        $this->id = (int) $id;

        if($this->viewUser()){
            $deleteUser = new \App\adms\Models\helper\AdmsDelete();
            $deleteUser->exeDelete("adms_users_final", "WHERE id=:id", "id={$this->id}");
    
            if ($deleteUser->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Usuário apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não apagado com sucesso!</p>";
                $this->result = false;
            }
        }else{
            $this->result = false;
        }
    }

    /**
     * Metodo faz a pesquisa para verificar se o usuário esta cadastrado no sistema, o resultado é enviado para a função deleteUser
     *
     * @return boolean
     */
    private function viewUser(): bool
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT id FROM adms_users_final WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewUser->getResult();
        
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            return false;
        }
    }
}
