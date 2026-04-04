<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar Equipamento
 * @author Daniel Canedo - docan2006@gmail.com
 */
class DeleteEquip
{

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    
    /**
     * Método apagar equipamento
     * Se existir o ID na URL instancia a MODELS para excluir o registro no banco de dados
     * Senão criar a mensagem de erro
     * Redireciona para a página listar equipamentos
     *
     * @param integer|string|null|null $id Receber o id do registro que deve ser excluido
     * @return void
     */
    public function index(int|string|null $id = null): void
    {

        if (!empty($id)) {
            $this->id = (int) $id;
            $deleteEquip = new \App\adms\Models\AdmsDeleteEquip();
            $deleteEquip->deleteEquip($this->id);            
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um equipamento!</p>";
        }

        $urlRedirect = URLADM . "list-equip/index";
        header("Location: $urlRedirect");

    }
}
