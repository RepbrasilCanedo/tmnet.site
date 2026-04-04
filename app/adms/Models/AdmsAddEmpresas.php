<?php
namespace App\adms\Models;
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsAddEmpresas
{
    private array|null $data;
    private bool $result = false;
    private array $listRegistryAdd;

    function getResult(): bool { return $this->result; }

    public function create(array $data)
    {
        $this->data = $data;

        // Limpa formatação de documento e CEP antes de validar e salvar
        $this->data['cnpjcpf'] = preg_replace('/\D/', '', $this->data['cnpjcpf']);
        $this->data['cep'] = preg_replace('/\D/', '', $this->data['cep']);

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);

        if ($valEmptyField->getResult()) {
            $this->valDocDuplicate();
        } else {
            $this->result = false;
        }
    }

    private function valDocDuplicate(): void
    {
        $readDoc = new \App\adms\Models\helper\AdmsRead();
        $readDoc->fullRead("SELECT id FROM adms_clientes WHERE cnpjcpf = :doc LIMIT 1", "doc={$this->data['cnpjcpf']}");
        if ($readDoc->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Este CNPJ/CPF já está cadastrado!</p>";
            $this->result = false;
        } else {
            $this->add();
        }
    }

    private function add(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['created'] = date("Y-m-d H:i:s");
        $this->data['situacao'] = 1;

        if ($_SESSION['adms_access_level_id'] > 2) {
            $this->data['empresa'] = $_SESSION['emp_user'];
        }

        $create = new \App\adms\Models\helper\AdmsCreate();
        $create->exeCreate("adms_clientes", $this->data);

        if ($create->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Cliente cadastrado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao cadastrar cliente.</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id, razao_social FROM adms_emp_principal ORDER BY razao_social ASC");
        return ['empresa' => $list->getResult()];
    }
}