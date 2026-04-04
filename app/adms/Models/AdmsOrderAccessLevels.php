<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Alterar ordem do nivel de acesso no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsOrderAccessLevels
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $data;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var array|null $resultBdPrev Recebe os registros do banco de dados */
    private array|null $resultBdPrev;

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
     * Metodo para alterar ordem do nivel de acesso
     * Recebe o ID do nivel de acesso que sera usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro.
     * @param integer $id
     * @return void
     */
    public function orderAccessLevels(int $id): void
    {
        $this->id = $id;

        $viewAccessLevel = new \App\adms\Models\helper\AdmsRead();
        $viewAccessLevel->fullRead("SELECT id, order_levels
                        FROM adms_access_levels                         
                        WHERE id=:id AND order_levels >:order_levels
                        LIMIT :limit", "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1");

        $this->resultBd = $viewAccessLevel->getResult();
        if ($this->resultBd) {
            $this->viewPrevAccessLevel();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para recuperar o ordem do nivel de acesso superior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function viewPrevAccessLevel(): void
    {
        $prevAccessLevel = new \App\adms\Models\helper\AdmsRead();
        $prevAccessLevel->fullRead(
            "SELECT id, order_levels 
                        FROM adms_access_levels
                        WHERE order_levels <:order_levels
                        AND order_levels >:order_levels_user
                        ORDER BY order_levels DESC
                        LIMIT :limit",
            "order_levels={$this->resultBd[0]['order_levels']}&order_levels_user=" . $_SESSION['order_levels'] . "&limit=1"
        );

        $this->resultBdPrev = $prevAccessLevel->getResult();
        if ($this->resultBdPrev) {
            $this->editMoveDown();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nível de acesso não encontrado!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para alterar a ordem do nivel de acesso superior para ser inferior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function editMoveDown(): void
    {
        $this->data['order_levels'] = $this->resultBd[0]['order_levels'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveDown = new \App\adms\Models\helper\AdmsUpdate();
        $moveDown->exeUpdate("adms_access_levels", $this->data, "WHERE id=:id", "id={$this->resultBdPrev[0]['id']}");

        if ($moveDown->getResult()) {
            $this->editMoveUp();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do nível de acesso não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para alterar a ordem do nivel de acesso inferior para ser superior
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function editMoveUp(): void
    {
        $this->data['order_levels'] = $this->resultBdPrev[0]['order_levels'];
        $this->data['modified'] = date("Y-m-d H:i:s");

        $moveUp = new \App\adms\Models\helper\AdmsUpdate();
        $moveUp->exeUpdate("adms_access_levels", $this->data, "WHERE id=:id", "id={$this->resultBd[0]['id']}");

        if ($moveUp->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Ordem do nível de acesso editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Ordem do nível de acesso não editado com sucesso!</p>";
            $this->result = false;
        }
    }
}
