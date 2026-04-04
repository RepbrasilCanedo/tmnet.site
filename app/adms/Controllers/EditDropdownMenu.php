<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar pagina dropdown
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditDropdownMenu
{

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /** @var int|string|null $level Recebe o nivel de acesso */
    private int|string|null $level;

    /** @var int|string|null $pag Recebe o numero da pagina */
    private int|string|null $pag;

    /**
     * Metodo editar pagina dropdown
     * Receber o id da pagina dropdown que deve ser editado
     * 
     * Se os parametros id, level e pag são diferente de vazio, instancia a MODELS para editar pagina dropdown. Se não existir redireciona para o listar nivel de acesso.
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->id = $id;
        $this->level = filter_input(INPUT_GET, "level", FILTER_SANITIZE_NUMBER_INT);
        $this->pag = filter_input(INPUT_GET, "pag", FILTER_SANITIZE_NUMBER_INT);

        if ((!empty($this->id)) and (!empty($this->level)) and (!empty($this->pag))) {
            $editDropdownMenu = new \App\adms\Models\AdmsEditDropdownMenu();
            $editDropdownMenu->editDropdownMenu($this->id);

            $urlRedirect = URLADM . "list-permission/index/{$this->pag}?level={$this->level}";
            header("Location: $urlRedirect");
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar a página!</p>";
            $urlRedirect = URLADM . "list-access-levels/index";
            header("Location: $urlRedirect");
        }
    }
}
