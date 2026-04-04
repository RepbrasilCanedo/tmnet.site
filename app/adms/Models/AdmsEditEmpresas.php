<?php
namespace App\adms\Models;
if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsEditEmpresas
{
    private bool $result = false;
    private array|null $resultBd;
    private int|string|null $id;
    private array|null $data;
    private array|null $listRegistryAdd;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewEmpresas(int $id): void
    {
        $this->id = $id;
        $viewEmpresas = new \App\adms\Models\helper\AdmsRead();
        $viewEmpresas->fullRead("SELECT id, razao_social, nome_fantasia, cnpjcpf, cep, logradouro, bairro, cidade, uf, situacao 
                FROM adms_clientes WHERE id=:id LIMIT 1", "id={$this->id}");
        $this->resultBd = $viewEmpresas->getResult();
        if (!$this->resultBd) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cliente não encontrado!</p>";
            $this->result = false;
        } else { $this->result = true; }
    }

    public function update(array $data): void
    {
        $this->data = $data;

        // Limpeza de caracteres especiais
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
        // Verifica se o CNPJ/CPF já existe em OUTRO registro que não seja o atual
        $readDoc = new \App\adms\Models\helper\AdmsRead();
        $readDoc->fullRead("SELECT id FROM adms_clientes WHERE cnpjcpf = :doc AND id <> :id LIMIT 1", 
            "doc={$this->data['cnpjcpf']}&id={$this->data['id']}");
        
        if ($readDoc->getResult()) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Este CNPJ/CPF já está cadastrado para outro cliente!</p>";
            $this->result = false;
        } else {
            $this->edit();
        }
    }

    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upEmpresas = new \App\adms\Models\helper\AdmsUpdate();
        $upEmpresas->exeUpdate("adms_clientes", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upEmpresas->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Empresa editada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não houve alterações ou falha no banco.</p>";
            $this->result = false;
        }
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
        return ['sit_empresas' => $list->getResult()];
    }
}