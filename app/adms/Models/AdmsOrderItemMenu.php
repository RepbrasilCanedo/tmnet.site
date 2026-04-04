<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Alterar ordem do item de menu no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsOrderItemMenu
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $data;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var array|null $resultBdPrev Recebe os registros do banco de dados */
    private array|null $resultBdPrev;

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
     * Metodo para alterar ordem do item de menu
     * Recebe o ID do item de menu que sera usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro.
     * @param integer $id
     * @return void
     */
    public function orderItemMenu(int $id): void
    {
        $this->id = $id;

        $viewItemMenu = new \App\adms\Models\helper\AdmsRead();
        $viewItemMenu->fullRead("SELECT id, order_item_menu 
                            FROM adms_items_menus 
                            WHERE id=:id 
                            LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewItemMenu->getResult();        
        if ($this->resultBd) {
            $this->viewPrevItemMenu();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para recuperar o ordem do item de menu superior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function viewPrevItemMenu(): void
    {
        $prevOrderItemMenu = new \App\adms\Models\helper\AdmsRead();
        $prevOrderItemMenu->fullRead("SELECT id, order_item_menu 
                            FROM adms_items_menus 
                            WHERE order_item_menu <:order_item_menu
                            ORDER BY order_item_menu DESC 
                            LIMIT :limit","order_item_menu={$this->resultBd[0]['order_item_menu']}&limit=1");

        $this->resultBdPrev = $prevOrderItemMenu->getResult();        
        if ($this->resultBdPrev) {
            $this->editMoveDown();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para alterar a ordem do item de menu superior para ser inferior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function editMoveDown(): void
    {
        $this->data['order_item_menu'] = $this->resultBd[0]['order_item_menu'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_items_menus", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");

        if ($moveDown->getResult()) {
            $this->editMoveUp();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do item de menu não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para alterar a ordem do item de menu inferior para ser superior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function editMoveUp(): void
    {
        $this->data['order_item_menu'] = $this->resultBdPrev[0]['order_item_menu'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_items_menus", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if ($moveUp->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Ordem do item de menu editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do item de menu não editado com sucesso!</p>";
            $this->result = false;
        }
    }
}
