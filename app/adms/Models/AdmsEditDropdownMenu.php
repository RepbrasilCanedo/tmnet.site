<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Models editar pagina dropdown
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditDropdownMenu
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var array|null $data Recebe as informacoes que deve ser salvo no banco de dados */
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
     * Metodo recebe como parametro o ID que sera usado para verificar se tem o registro cadastrado no banco de dados
     * @param integer $id
     * @return void
     */
    public function editDropdownMenu(int $id): void
    {
        $this->id = $id;

        $viewPrintMenu = new \App\adms\Models\helper\AdmsRead();
        $viewPrintMenu->fullRead(
            "SELECT lev_pag.id, lev_pag.dropdown
                            FROM adms_levels_pages lev_pag
                            INNER JOIN adms_access_levels AS lev ON lev.id=lev_pag.adms_access_level_id
                            LEFT JOIN adms_pages AS pag ON pag.id=lev_pag.adms_page_id  
                            WHERE lev_pag.id =:id
                            AND lev.order_levels >=:order_levels
                            AND (((SELECT permission 
                            FROM adms_levels_pages 
                            WHERE adms_page_id = lev_pag.adms_page_id 
                            AND adms_access_level_id = {$_SESSION['adms_access_level_id']}) = 1) 
                            OR (publish = 1))
                            LIMIT :limit",
            "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1"
        );

        $this->resultBd = $viewPrintMenu->getResult();
        if ($this->resultBd) {
            $this->edit();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar uma página!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para alterar pagina dropdown no banco de dados.
     * Retorna TRUE quando editar no banco de dados.
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function edit(): void
    {
        if($this->resultBd[0]['dropdown'] == 1){
            $this->data['dropdown'] = 2;
        }else{
            $this->data['dropdown'] = 1;
        }
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upPrintMenu = new \App\adms\Models\helper\AdmsUpdate();
        $upPrintMenu->exeUpdate("adms_levels_pages", $this->data, "WHERE id=:id", "id={$this->id}");

        if ($upPrintMenu->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Página dropdown editada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página dropdown não editada com sucesso!</p>";
            $this->result = false;
        }
    }
}
