<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar pagina no item de menu
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditPageMenu
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $data;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $listRegistryEdit;

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
     * Metodo para visualizar os detalhes da pagina no item de menu
     * Recebe o ID da pagina no item de menu que será usado como parametro na pesquisa
     * Retorna FALSE se houver algum erro.
     * @param integer $id
     * @return void
     */
    public function viewPageMenu(int $id): bool
    {
        $this->id = $id;

        $viewPageMenu = new \App\adms\Models\helper\AdmsRead();
        $viewPageMenu->fullRead("SELECT lev_pag.id, lev_pag.adms_items_menu_id,
                        pag.name_page
                        FROM adms_levels_pages AS lev_pag
                        INNER JOIN adms_pages AS pag ON pag.id=lev_pag.adms_page_id 
                        INNER JOIN adms_access_levels AS lev ON lev.id=lev_pag.adms_access_level_id
                        WHERE lev_pag.id=:id 
                        AND lev.order_levels >=:order_levels
                        AND (((SELECT permission 
                        FROM adms_levels_pages 
                        WHERE adms_page_id = lev_pag.adms_page_id 
                        AND adms_access_level_id = {$_SESSION['adms_access_level_id']}) = 1) 
                        OR (publish = 1))
                        LIMIT :limit", "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1");

        $this->resultBd = $viewPageMenu->getResult();
        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Página no item de menu não encontrado!</p>";
            $this->result = false;
            return false;
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
            if ($this->viewPageMenu($this->data['id'])) {
                $this->edit();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Sem permissão para editar!</p>";
                $this->result = false;
            }
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
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upColor = new \App\adms\Models\helper\AdmsUpdate();
        $upColor->exeUpdate("adms_levels_pages", $this->data, "WHERE id=:id", "id={$this->data['id']}");

        if ($upColor->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Item de menu da página editado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Item de menu da página não editado com sucesso!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead("SELECT id id_itm, name name_itm FROM adms_items_menus ORDER BY name ASC");
        $registry['itm'] = $list->getResult();

        $this->listRegistryEdit = ['itm' => $registry['itm']];

        return $this->listRegistryEdit;
    }
}
