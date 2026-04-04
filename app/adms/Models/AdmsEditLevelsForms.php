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
class AdmsEditLevelsForms
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $listRegistryAdd;

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
     * Metodo verificar se tem o registro cadastrado no banco de dados
     * @return void
     */
    public function viewLevelsForms(): void
    {

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead(
            "SELECT id, adms_access_level_id, adms_sits_user_id 
                            FROM adms_levels_forms
                            ORDER BY id ASC
                            LIMIT :limit",
            "limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página de configuração não encontrada!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo recebe como parametro a informacao que sera editada
     * Instancia o helper AdmsValEmptyField para validar os campos do formulario
     * Chama a funcao edit para enviar as informacoes para o banco de dados
     * @param array|null $data
     * @return void
     */
    public function update(array $data = null): void
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
     * Metodo envia as informacoes editadas para o banco de dados
     * @return void
     */
    private function edit(): void
    {
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upLevelsForm = new \App\adms\Models\helper\AdmsUpdate();
        $upLevelsForm->exeUpdate("adms_levels_forms", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upLevelsForm->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Configuração editada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Configuração não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo pesquisa as informacoes que serao usadas no dropdown do formulario
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_users ORDER BY name ASC");
        $registry['sit'] = $list->getResult();

        $list->fullRead("SELECT id id_lev, name name_lev 
                        FROM adms_access_levels 
                        WHERE order_levels >:order_levels
                        ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
        $registry['lev'] = $list->getResult();

        $this->listRegistryAdd = ['sit' => $registry['sit'], 'lev' => $registry['lev']];

        return $this->listRegistryAdd;
    }
}
