<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar Equipamento no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */

class AdmsEditEquip
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
    public function viewEquip(int $id): void
    {
        $this->id = $id;
        $viewEquip = new \App\adms\Models\helper\AdmsRead();
        $viewEquip->fullRead("SELECT equip.id, equip.name, typ.name name_typ, equip.serie, modelo.name name_modelo, mar.name name_mar, 
        emp.nome_fantasia nome_fantasia_emp, cont.num_cont num_cont_equip, sit.name name_sit, equip.created, equip.modified
        FROM adms_equipamentos AS equip 
        LEFT JOIN adms_type_equip AS typ ON typ.id=equip.type_id 
        LEFT JOIN adms_model AS modelo ON modelo.id=equip.modelo_id 
        LEFT JOIN adms_marca AS mar ON mar.id=equip.marca_id 
        LEFT JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
        LEFT JOIN adms_contr AS cont ON cont.id=equip.cont_id 
        LEFT JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id
        WHERE equip.id=:id
        LIMIT :limit", "id={$this->id}&limit=1");;

        $this->resultBd = $viewEquip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento  não encontrado!</p>";
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
     * Metodo envia as informações editadas para o banco de dados
     * @return void
     */
    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upEquip = new \App\adms\Models\helper\AdmsUpdate();
        $upEquip->exeUpdate("adms_equipamentos", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upEquip->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Equipamento editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não editado com sucesso!</p>";
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

        $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_equip ORDER BY name ASC");
        $registry['type_equip'] = $list->getResult();

        $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
        $registry['mod_equip'] = $list->getResult();

        $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
        $registry['marca_equip'] = $list->getResult();

        $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa ORDER BY nome_fantasia ASC");
        $registry['emp_equip'] = $list->getResult();

        $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
        $registry['sit_equip'] = $list->getResult();

        $this->listRegistryEdit = ['type_equip' => $registry['type_equip'], 'mod_equip' => $registry['mod_equip'],
        'marca_equip' => $registry['marca_equip'], 'emp_equip' => $registry['emp_equip'], 'sit_equip' => $registry['sit_equip']];

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

        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {

            if ($_SESSION['adms_access_level_id'] == 10) {

                $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_equip ORDER BY name ASC");
                $registry['type_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
                $registry['mod_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
                $registry['marca_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
                $registry['sit_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa WHERE id= :id ORDER BY nome_fantasia ASC", "id={$_SESSION['emp_user']}");
                $registry['emp_equip'] = $list->getResult();

                $this->listRegistryAdd = [
                    'type_equip' => $registry['type_equip'], 'mod_equip' => $registry['mod_equip'],
                    'marca_equip' => $registry['marca_equip'], 'emp_equip' => $registry['emp_equip'], 'sit_equip' => $registry['sit_equip']];
                    
            } else if ($_SESSION['adms_access_level_id'] == 4) {

                $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_equip ORDER BY name ASC");
                $registry['type_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
                $registry['mod_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
                $registry['marca_equip'] = $list->getResult();

                

                $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
                $registry['sit_equip'] = $list->getResult();

                $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa WHERE contrato = :contrato  ORDER BY nome_fantasia ASC", "contrato={$_SESSION['set_Contr']}");
                $registry['emp_equip'] = $list->getResult();

                $this->listRegistryAdd = [
                    'type_equip' => $registry['type_equip'], 'mod_equip' => $registry['mod_equip'],
                    'marca_equip' => $registry['marca_equip'], 'emp_equip' => $registry['emp_equip'], 'sit_equip' => $registry['sit_equip']];
            }
        } else {

            $list->fullRead("SELECT id id_typ, name name_typ FROM adms_type_equip ORDER BY name ASC");
            $registry['type_equip'] = $list->getResult();

            $list->fullRead("SELECT id id_modelo, name name_modelo FROM adms_model ORDER BY name ASC");
            $registry['mod_equip'] = $list->getResult();

            $list->fullRead("SELECT id id_mar, name name_mar FROM adms_marca ORDER BY name ASC");
            $registry['marca_equip'] = $list->getResult();

            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa ORDER BY nome_fantasia ASC");
            $registry['emp_equip'] = $list->getResult();

            $list->fullRead("SELECT id id_sit, name name_sit FROM adms_sits_empr_unid ORDER BY name ASC");
            $registry['sit_equip'] = $list->getResult();

            $list->fullRead("SELECT id id_cont, num_cont cont_id FROM  adms_contr AS cont ORDER BY num_cont ASC");
            $registry['emp_cont'] = $list->getResult();

            $this->listRegistryAdd = [
                'type_equip' => $registry['type_equip'], 'mod_equip' => $registry['mod_equip'],
                'marca_equip' => $registry['marca_equip'], 'emp_equip' => $registry['emp_equip'], 'sit_equip' => $registry['sit_equip'], 'emp_cont' => $registry['emp_cont']
            ];
        }

        return $this->listRegistryAdd;
    }
}
