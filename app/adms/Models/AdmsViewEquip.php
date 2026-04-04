<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar detalhes da página no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewEquip
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

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
     * Metodo para visualizar os detalhes da página
     * Recebe o ID da página que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro
     * @param integer $id
     * @return void
     */
    public function viewEquip(int $id): void
    {
        $this->id = $id;

        $viewEquip = new \App\adms\Models\helper\AdmsRead();
        $viewEquip->fullRead("SELECT equip.id as id_equip, equip.name as name_equip, typ.name as name_typ, equip.serie as serie_equip, modelo.name as name_modelo, mar.name as name_mar, 
                    emp.nome_fantasia as nome_fantasia_emp, cont.num_cont as num_cont_equip, sit.name as name_sit, equip.created, equip.modified
                    FROM adms_equipamentos AS equip 
                    INNER JOIN adms_type_equip AS typ ON typ.id=equip.type_id 
                    INNER JOIN adms_model AS modelo ON modelo.id=equip.modelo_id 
                    INNER JOIN adms_marca AS mar ON mar.id=equip.marca_id 
                    INNER JOIN adms_empresa AS emp ON emp.id=equip.empresa_id 
                    LEFT JOIN adms_contr AS cont ON cont.id=equip.cont_id 
                    INNER JOIN adms_sits_empr_unid AS sit ON sit.id=equip.sit_id
                    WHERE equip.id=:id_equip
                    LIMIT :limit", "id_equip={$this->id}&limit=1");

        $this->resultBd = $viewEquip->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não encontrado!</p>";
            $this->result = false;
        }
    }
}
