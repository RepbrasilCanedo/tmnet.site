<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar Produto no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */

class AdmsEditProd
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;


    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array $listRegistryAdd;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var array Recebe as informações que serão usadas no dropdown do formulário*/
    private array $listRegistryEdit;

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
    public function viewProd(int $id): void
    {
        $this->id = $id;
        $viewProd = new \App\adms\Models\helper\AdmsRead();
        $viewProd->fullRead("SELECT prod.id, prod.name as name_prod, typ.name as name_type, prod.serie as serie_prod, 
                prod.modelo_id as name_modelo, prod.marca_id as name_mar, clie.razao_social as razao_social_clie, clie.nome_fantasia as nome_fantasia_clie, prod.venc_contr as venc_contr_prod,  
                contr.name as name_contr_id, prod.dias, prod.inicio_contr, prod.inf_adicionais as inf_adicionais, sit.name as name_sit, prod.created, prod.modified
                FROM adms_produtos AS prod  
                INNER JOIN adms_type_equip AS typ ON typ.id=prod.type_id 
                INNER JOIN adms_clientes AS clie ON clie.id=prod.cliente_id 
                INNER JOIN adms_sit_equip AS sit ON sit.id=prod.sit_id                 
                INNER JOIN adms_contr AS contr ON contr.id=prod.contr_id
                WHERE prod.id= :prod_id", "prod_id={$this->id}");

        $this->resultBd = $viewProd->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Produto  não encontrado!</p>";
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

        $upProd = new \App\adms\Models\helper\AdmsUpdate();
        $upProd->exeUpdate("adms_produtos", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upProd->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Produto editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Produto não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    /*
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_prod ORDER BY name ASC");
        $registry['type_prod'] = $list->getResult();

        $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
        $registry['mod_prod'] = $list->getResult();

        $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
        $registry['marca_prod'] = $list->getResult();

        $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa ORDER BY nome_fantasia ASC");
        $registry['emp_prod'] = $list->getResult();

        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
        $registry['sit_prod'] = $list->getResult();

        $this->listRegistryEdit = ['type_prod' => $registry['type_prod'], 'mod_prod' => $registry['mod_prod'],
        'marca_prod' => $registry['marca_prod'], 'emp_prod' => $registry['emp_prod'], 'sit_prod' => $registry['sit_prod']];

        return $this->listRegistryEdit;
    }
    */
    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        if (($_SESSION['adms_access_level_id'] > 2) and ($_SESSION['adms_access_level_id'] <> 7)) {

            if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] == 12)) {

                $list->fullRead("SELECT id as id_typ, name as name_typ FROM adms_type_equip");
                $registry['type_prod'] = $list->getResult();

                $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
                $registry['mod_prod'] = $list->getResult();

                $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
                $registry['marca_prod'] = $list->getResult();

                $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sit_equip");
                $registry['sit_prod'] = $list->getResult();

                $list->fullRead("SELECT id, name FROM adms_contr");
                $registry['contr_id'] = $list->getResult();

                $this->listRegistryAdd = [
                    'type_prod' => $registry['type_prod'],
                    'mod_prod' => $registry['mod_prod'],
                    'marca_prod' => $registry['marca_prod'],
                    'sit_prod' => $registry['sit_prod'],
                    'contr_id' => $registry['contr_id']
                ];
            }
        } else {

            $list->fullRead("SELECT id as id_typ, name as name_typ FROM adms_type_equip");
            $registry['type_prod'] = $list->getResult();

            $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
            $registry['mod_prod'] = $list->getResult();

            $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
            $registry['marca_prod'] = $list->getResult();

            $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sit_equip");
            $registry['sit_prod'] = $list->getResult();

            $list->fullRead("SELECT id, nome_fantasia FROM adms_clientes WHERE id= :id", "id={$_SESSION['emp_user']}");
            $registry['emp_prod'] = $list->getResult();

            $list->fullRead("SELECT id, name FROM adms_contr");
            $registry['contr_id'] = $list->getResult();

            $this->listRegistryAdd = [
                'type_prod' => $registry['type_prod'],
                'mod_prod' => $registry['mod_prod'],
                'marca_prod' => $registry['marca_prod'],
                'sit_prod' => $registry['sit_prod'],
                'contr_id' => $registry['contr_id']
            ];
        }

        return $this->listRegistryAdd;
    }
}
