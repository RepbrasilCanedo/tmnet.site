<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar detalhes do contrato no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewContr
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var string|null $page Recebe a páginação */
    private string|null $resultContCham;

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
     * Metodo para visualizar os detalhes do contrato
     * Recebe o ID da página que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro
     * @param integer $id
     * @return void
     */
    public function viewContr(int $id): void
    {
        $this->id = $id;

        $viewContr = new \App\adms\Models\helper\AdmsRead();
        $viewContr->fullRead("SELECT cont.id as id_cont, cont.name as name_cont, sit.name AS situacao, emp.nome_fantasia AS nome_fantasia_emp, cont.created as created, cont.modified  as modified
                FROM adms_contr AS cont 
                INNER JOIN adms_emp_principal AS emp ON emp.id=cont.empresa_id      
                INNER JOIN adms_contr_sit AS sit ON sit.id=cont.sit_cont   
                WHERE cont.id = :cont_id", "cont_id={$this->id}");


        $this->resultBd = $viewContr->getResult();
        if ($this->resultBd) {
            +$this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contratos não encontrado!</p>";
            $this->result = false;
        }
    }
}
