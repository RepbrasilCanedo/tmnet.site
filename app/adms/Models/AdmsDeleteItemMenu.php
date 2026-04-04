<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar item de menu no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteItemMenu
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
    public function deleteItemMenu(int $id): void
    {
        $this->id = (int) $id;

        if (($this->viewItemMenu()) and ($this->checkItemMenuUsed())) {
            $deleteItemMenu = new \App\adms\Models\helper\AdmsDelete();
            $deleteItemMenu->exeDelete("adms_items_menus", "WHERE id =:id", "id={$this->id}");

            if ($deleteItemMenu->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Item de menu apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não apagado com sucesso!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo verifica se a item de menu esta cadastrada na tabela e envia o resultado para a função deleteItemMenu
     * @return boolean
     */
    private function viewItemMenu(): bool
    {

        $viewItemMenu = new \App\adms\Models\helper\AdmsRead();
        $viewItemMenu->fullRead(
            "SELECT id
                            FROM adms_items_menus                           
                            WHERE id=:id
                            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewItemMenu->getResult();
        if ($this->resultBd) {
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            return false;
        }
    }

    /**
     * Metodo verifica se tem permissao cadastrada utilizando o item de menu a ser excluido, caso tenha a exclusão não é permitida
     * O resultado da pesquisa é enviada para a função deleteItemMenu
     * @return boolean
     */
    private function checkItemMenuUsed(): bool
    {
        $viewItemMenuUsed = new \App\adms\Models\helper\AdmsRead();
        $viewItemMenuUsed->fullRead("SELECT id FROM adms_levels_pages WHERE adms_items_menu_id =:adms_items_menu_id LIMIT :limit", "adms_items_menu_id={$this->id}&limit=1");
        if ($viewItemMenuUsed->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não pode ser apagado, há permissão cadastrada com esse item de menu!</p>";
            return false;
        } else {
            return true;
        }
    }
}
