<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller da pagina alterar ordem do item de menu
 * @author Daniel Canedo - docan2006@gmail.com
 */
class OrderItemMenu
{
    /** @var array|string|null $pag Recebe o numero da pagina */
    private array|string|null $pag;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Metodo alterar ordem do item de menu 
     * Recebe como parametro o ID que sera usado para pesquisar as informacoes no banco de dados e instancia a MODELS AdmsOrderItemMenu
     * Apos editar ou erro redireciona para o listar item de menu
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);
        
        if ((!empty($id)) and (!empty($this->pag))) {
            $this->id = (int) $id;

            $viewItemMenu = new \App\adms\Models\AdmsOrderItemMenu();
            $viewItemMenu->orderItemMenu($this->id);
            if ($viewItemMenu->getResult()) {
                $urlRedirect = URLADM . "list-item-menu/index/{$this->pag}";
                header("Location: $urlRedirect");
            } else {
                $urlRedirect = URLADM . "list-item-menu/index/{$this->pag}";
                header("Location: $urlRedirect");
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $urlRedirect = URLADM . "list-item-menu/index";
            header("Location: $urlRedirect");
        }
    }
}
