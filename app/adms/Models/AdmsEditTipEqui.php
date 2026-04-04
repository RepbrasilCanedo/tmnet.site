<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar tipo de equipamento no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditTipEqui
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $listRegistryAdd;

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
    public function viewTipEqui(int $id): void
    {
        $this->id = $id;

        $viewTipEqui = new \App\adms\Models\helper\AdmsRead();
        $viewTipEqui->fullRead( "SELECT id, name, empresa_id, sit_id  FROM adms_type_equip WHERE id=:id LIMIT :limit", "id={$this->id}&limit=1");

        $this->resultBd = $viewTipEqui->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de equipamento não encontrado!</p>";
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
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upTipEqui = new \App\adms\Models\helper\AdmsUpdate();
        $upTipEqui->exeUpdate("adms_type_equip", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upTipEqui->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Tipo de equipamento editado com suvesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de equipamento não editado com sucesso!</p>";
            $this->result = false;
        }
    }

           /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();


        $list->fullRead("SELECT id, name FROM adms_sits_users");
        $registry['sittipequi'] = $list->getResult();

        $this->listRegistryAdd = ['sittipequi' => $registry['sittipequi']];


        return $this->listRegistryAdd;
    }
}
