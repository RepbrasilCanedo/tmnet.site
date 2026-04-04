<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller apagar configuração de email
 * @author Daniel Canedo - docan2006@gmail.com
 */
class DeleteConfEmails
{

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;
    
    /**
     * Método apagar configuração de email
     * Se existir o ID na URL instancia a MODELS para excluir o registro no banco de dados
     * Senão criar a mensagem de erro
     * Redireciona para a página listar configuração de email
     *
     * @param integer|string|null|null $id
     * @return void
     */
    public function index(int|string|null $id = null): void
    {

        if (!empty($id)) {
            $this->id = (int) $id;
            $deleteConfEmails = new \App\adms\Models\AdmsDeleteConfEmails();
            $deleteConfEmails->deleteConfEmails($this->id);            
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar uma configuração de email!</p>";
        }

        $urlRedirect = URLADM . "list-conf-emails/index";
        header("Location: $urlRedirect");

    }
}
