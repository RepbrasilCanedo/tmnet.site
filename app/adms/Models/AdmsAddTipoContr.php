<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Cadastrar Tipo de Contrato no banco de dados
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsAddTipoContr
{
    private array|null $data;
    private bool $result = false;

    public function getResult(): bool
    {
        return $this->result;
    }

    /** * Valida e inicia o cadastro do tipo de contrato
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

    /** * Executa o cadastro no banco de dados
     */
    private function add(): void
    {
        date_default_timezone_set('America/Bahia');
        
        $this->data['empresa_id'] = $_SESSION['emp_user'] ?? $_SESSION['user_id'];
        $this->data['created'] = date("Y-m-d H:i:s");

        $createContr = new \App\adms\Models\helper\AdmsCreate();
        $createContr->exeCreate("adms_tipo_contrato", $this->data);

        if ($createContr->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Tipo de Contrato cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível cadastrar o Tipo de Contrato!</p>";
            $this->result = false;
        }
    }
}