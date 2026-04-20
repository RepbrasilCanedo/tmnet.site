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
class AdmsDeleteUsers
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
     * @param integer $id
     * @return void
     */
    public function deleteUser(int $id): void
    {
        $this->id = (int) $id;

        // ========================================================================
        // DOCAN FIX: TRAVA DE SEGURANÇA CONTRA EXCLUSÃO DE ATLETAS FILIADOS
        // ========================================================================
        if ($this->checkAffiliation()) {
            $_SESSION['msg'] = "<p class='alert-warning'>🚫 <b>Ação Bloqueada:</b> Este atleta não pode ser excluído pois possui filiação ativa com um ou mais clubes na plataforma!</p>";
            $this->result = false;
            return; // Aborta a exclusão aqui mesmo
        }

        if($this->viewUser()){
            $deleteUser = new \App\adms\Models\helper\AdmsDelete();
            $deleteUser->exeDelete("adms_users", "WHERE id =:id", "id={$this->id}");
    
            if ($deleteUser->getResult()) {
                $this->deleteImg();
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
     * Verifica na tabela ponte se o atleta está filiado a algum clube
     *
     * @return boolean Retorna true se encontrar filiação, false se estiver livre
     */
    private function checkAffiliation(): bool
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id FROM adms_atleta_clube WHERE adms_user_id = :id LIMIT 1", "id={$this->id}");
        
        if ($read->getResult()) {
            return true; // Encontrou filiação (Bloqueia exclusão)
        }
        return false; // Limpo (Libera exclusão)
    }

    /**
     * Metodo faz a pesquisa para verificar se o usuário esta cadastrado no sistema
     *
     * @return boolean
     */
    private function viewUser(): bool
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        
        // DOCAN FIX: Corrigido de 'usr.image' para 'usr.imagem' para não quebrar a exclusão do diretório
        $viewUser->fullRead(
            "SELECT usr.id, usr.imagem
             FROM adms_users AS usr
             INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
             WHERE usr.id=:id AND lev.order_levels >:order_levels
             LIMIT :limit",
            "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            return false;
        }
    }

    /**
     * Metodo usado para apagar a imagem e o diretorio do usuário do servidor
     *
     * @return void
     */
    private function deleteImg(): void
    {
        // DOCAN FIX: Adaptado para a coluna correta (imagem)
        if((!empty($this->resultBd[0]['imagem'])) or ($this->resultBd[0]['imagem'] != null)){
            $this->delDirectory = "app/adms/assets/image/users/" . $this->resultBd[0]['id'];
            $this->delImg = $this->delDirectory . "/" . $this->resultBd[0]['imagem'];

            if(file_exists($this->delImg)){
                unlink($this->delImg);
            }

            if(file_exists($this->delDirectory)){
                rmdir($this->delDirectory);
            }
        }
    }
}