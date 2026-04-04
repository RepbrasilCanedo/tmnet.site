<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar item de menu no banco de dados
 *
  * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditItemMenu
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

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
     * Metodo recebe como parametro o ID que será usado para verificar se tem o registro cadastrado no banco de dados
     * @param integer $id
     * @return void
     */
    public function viewItemMenu(int $id): void
    {
        $this->id = $id;

        $viewItemMenu = new \App\adms\Models\helper\AdmsRead();
        $viewItemMenu->fullRead(
            "SELECT id, name, icon
                            FROM adms_items_menus
                            WHERE id=:id
                            LIMIT :limit",
            "id={$this->id}&limit=1"
        );

        $this->resultBd = $viewItemMenu->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo recebe como parametro a informação que será editada
     * Instancia o helper AdmsValEmptyField para validar os campos do formulário
     * Chama a função edit para enviar as informações para o banco de dados
     * @param array|null $data
     * @return void
     */
    public function update(array $data): void
    {
        $this->data = $data;

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo envia as informações editadas para o banco de dados
     * @return void
     */
    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upItemMenu = new \App\adms\Models\helper\AdmsUpdate();
        $upItemMenu->exeUpdate("adms_items_menus", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upItemMenu->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Item de menu editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu não editado com sucesso!</p>";
            $this->result = false;
        }
    }
}
