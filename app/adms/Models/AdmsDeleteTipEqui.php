<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar cor no banco de dados
 *
* @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsDeleteTipEqui
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Metodo recebe como parametro o ID do registro que será excluido
     * Chama as funções viewSit e checkColorUsed para fazer a confirmação do registro antes de excluir
     * @param integer $id
     * @return void
     */
    public function deleteTipEqui(int $id): void
    {
        $this->id = (int) $id;

            $deleteTipEqui = new \App\adms\Models\helper\AdmsDelete();
            $deleteTipEqui->exeDelete("adms_type_equip", "WHERE id =:id", "id={$this->id}");

            if ($deleteTipEqui->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Tipo de equipamento apagado com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de equipamento não apagado com sucesso!</p>";
                $this->result = false;
            }
    }
}
