<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar cor no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditAviso
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
    public function viewAviso(int $id): void
    {
        $this->id = $id;

        $viewAviso = new \App\adms\Models\helper\AdmsRead();
        $viewAviso->fullRead("SELECT id, empresa_id, logo, name, texto, tit_aviso, aviso, modified
                            FROM adms_info_login
                            WHERE  empresa_id= :empresa_id LIMIT :limit", 
                            "empresa_id={$_SESSION['emp_user']}&limit=1");


        $this->resultBd = $viewAviso->getResult();
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
        $this->edit();

    }

    /**
     * Metodo envia as informações editadas para o banco de dados
     * @return void
     */
    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upColor = new \App\adms\Models\helper\AdmsUpdate();
        $upColor->exeUpdate("adms_info_login", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upColor->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Aviso editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Aviso não editado com sucesso!</p>";
            $this->result = false;
        }
    }
}
