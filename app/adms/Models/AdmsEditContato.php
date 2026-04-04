<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar mensagem no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditContato
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
    public function viewContato(int $id): void
    {
        $this->id = $id;

        $viewContato = new \App\adms\Models\helper\AdmsRead();
        $viewContato->fullRead("SELECT mens.id as id_mens, mens.empresa_id, clie.nome_fantasia as nome_fantasia_clie, mens.assunto as assunto_mens, 
            mens.nome as nome_mens, mens.email as email_mens, mens.tel as tel_mens, mens.mensagem as mensagem_mens, mens.dia as dia_mens, mens.status as status_mens
            FROM sts_contacts_msgs AS mens
            INNER JOIN adms_clientes AS clie ON clie.id=mens.cliente_id
            WHERE mens.id = :id_mens","id_mens={$this->id}");
        

        $this->resultBd = $viewContato->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cor não encontrada!</p>";
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
        $this->data['status'] = "Cliente Contatado";
        $this->data['dia'] = date("Y-m-d H:i:s");

        $upColor = new \App\adms\Models\helper\AdmsUpdate();
        $upColor->exeUpdate("sts_contacts_msgs", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upColor->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Mensagem editada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Mensagem não editada com sucesso!</p>";
            $this->result = false;
        }
    }
}
