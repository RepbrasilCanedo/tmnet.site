<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar item de menu no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddItemMenu
{
    /** @var array|null $data Recebe as informacoes do formulario */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    

    /** @var array|null $data Recebe as informacoes do formulario */
    private array|null $resultadoBd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Recebe os valores do formulario.
     * Instancia o helper "AdmsValEmptyField" para verificar se todos os campos estao preenchidos      
     * Verifica se todos os campos estao preenchidos e instancia o metodo "add" para cadastrar no banco de dados
     * Instancia o metodo "viewLastItemMenu" para verificar qual he a ultima ordem que esta cadastrada no banco de dados
     * Retorna FALSE quando algum campo esta vazio
     * 
     * @param array $data Recebe as informações do formulário
     * 
     * @return void
     */
    public function create(array $data): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->add();
        } else {
            $this->result = false;
        }
    }

    /** 
     * Cadastrar item de menu no banco de dados
     * Retorna TRUE quando cadastrar o nivel de acesso com sucesso
     * Retorna FALSE quando nao cadastrar o nivel de acesso
     * 
     * @return void
     */
    private function add(): void
    {
        if ($this->viewLastItemMenu()) {
            $this->data['created'] = date("Y-m-d H:i:s");

            $createItemMenu = new \App\adms\Models\helper\AdmsCreate();
            $createItemMenu->exeCreate("adms_items_menus", $this->data);

            if ($createItemMenu->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Item de menu cadastrado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não cadastrado com sucesso!</p>";
                $this->result = false;
            }
        }
    }

    /** 
     * Metodo para verificar qual he a ultima ordem que esta cadastrada no banco de dados
     */
    private function viewLastItemMenu()
    {
        $viewLastItemMenu = new \App\adms\Models\helper\AdmsRead();
        $viewLastItemMenu->fullRead("SELECT order_item_menu FROM adms_items_menus ORDER BY order_item_menu DESC LIMIT 1");
        $this->resultadoBd = $viewLastItemMenu->getResult();
        if ($this->resultadoBd) {
            $this->data['order_item_menu'] = $this->resultadoBd[0]['order_item_menu'] + 1;
            return true;
        } else {
            $this->data['order_item_menu'] = 1;
        }
    }
}
