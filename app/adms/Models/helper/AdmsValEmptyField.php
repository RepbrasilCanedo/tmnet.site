<?php

namespace App\adms\Models\helper;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe genérica para validar se os campos estão preenchidos
 *
  * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsValEmptyField
{
    /** @var array|null $data Recebe o array de dados que deve ser validado */
    private array|null $data;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Validar se todos os campos estão preenchidos.
     * Recebe o array de dados que deve ser validado.
     * Retorna TRUE quando todos os campos estão preenchidos.
     * Retorna FALSE quando algum campo está vazio.
     * 
     * @param array $data Recebe o array de dados que deve ser validado.
     * 
     * @return void
     */
    public function valField(array $data): void
    {
        $this->data = $data;
        $this->data = array_map('strip_tags', $this->data);
        $this->data = array_map('trim', $this->data);

        if(in_array('', $this->data)){
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário preencher todos os campos!</p>";
            $this->result = false;
        }else{
            $this->result = true;
        }
    }
}
