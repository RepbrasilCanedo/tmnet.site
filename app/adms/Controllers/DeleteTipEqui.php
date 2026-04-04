<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar tipo de equipamento
 * @author Daniel Canedo - docan2006@gmail.com
 */
class DeleteTipEqui
{

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    
    /**
     * Método apagar cor
     * Se existir o ID na URL instancia a MODELS para excluir o registro no banco de dados
     * Senão criar a mensagem de erro
     * Redireciona para a página listar cor
     *
     * @param integer|string|null|null $id Receber o id do registro que deve ser excluido
     * @return void
     */
    public function index(int|string|null $id = null): void
    {

        if (!empty($id)) {
            $this->id = (int) $id;
            $deleteTipEqui = new \App\adms\Models\AdmsDeleteTipEqui();
            $deleteTipEqui->deleteTipEqui($this->id);            
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um tipo de equipamento!</p>";
        }

        $urlRedirect = URLADM . "list-tip-equi/index";
        header("Location: $urlRedirect");

    }
}
