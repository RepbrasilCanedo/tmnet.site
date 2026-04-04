<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar o perfil do usuario
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewProfile
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

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
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo para visualizar o perfil do usuário
     * Recebe o ID do usuário que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro
     * @return void
     */
    public function viewProfile(): void
    {
            
            $viewUser = new \App\adms\Models\helper\AdmsRead();
            $viewUser->fullRead("SELECT name, apelido, email, tel_1, tel_2, imagem 
                            FROM adms_users
                            WHERE id=:id
                            LIMIT :limit", "id=" . $_SESSION['user_id'] . "&limit=1");

        $this->resultBd = $viewUser->getResult();
        

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $this->result = false;
        }
    }
}