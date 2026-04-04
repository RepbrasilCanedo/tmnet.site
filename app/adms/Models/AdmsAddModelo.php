<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar Modelo no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddModelo
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
     * Cadastrar Modelo  no banco de dados
     * Retorna TRUE quando cadastrar o Modelo com sucesso
     * Retorna FALSE quando não cadastrar o modelo
     * 
     * @return void
     */
    private function add(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['created'] = date("Y-m-d H:i:s");

        $createModelo = new \App\adms\Models\helper\AdmsCreate();
        $createModelo->exeCreate("adms_model", $this->data);

        if ($createModelo->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Modelo cadastrada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não cadastrada com sucesso!</p>";
            $this->result = false;
        }
    }
}
