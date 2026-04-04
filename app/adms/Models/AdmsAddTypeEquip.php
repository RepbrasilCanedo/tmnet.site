<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar tipos de equipamento no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddTypeEquip
{
    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    private array $listRegistryAdd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Recebe os valores do formulário.
     * Instancia o helper "AdmsValEmptyField" para verificar se todos os campos estão preenchidos 
     * Verifica se todos os campos estão preenchidos e instancia o método "valInput" para validar os dados dos campos
     * Retorna FALSE quando algum campo está vazio
     * 
     * @param array $data Recebe as informações do formulário
     * 
     * @return void
     */
    public function create(array $data = null): void
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
     * Cadastrar tipo  no banco de dados
     * Retorna TRUE quando cadastrar o tipo com sucesso
     * Retorna FALSE quando não cadastrar o tipo
     * 
     * @return void
     */
    private function add(): void
    {
        $this->data['created'] = date("Y-m-d H:i:s");

        $createTypeEquip = new \App\adms\Models\helper\AdmsCreate();
        $createTypeEquip->exeCreate("adms_type_equip", $this->data);

        if ($createTypeEquip->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Tipo cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo não cadastrado com sucesso!</p>";
            $this->result = false;
        }
    }
}
