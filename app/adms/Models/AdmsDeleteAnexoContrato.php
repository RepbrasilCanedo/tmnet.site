<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Apagar Anexo do Contrato no banco de dados e o arquivo físico
 */
class AdmsDeleteAnexoContrato
{
    private bool $result = false;
    private array|null $resultBd;
    private int|null $contId = null;

    public function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Retorna o ID do contrato para a Controller poder redirecionar de volta para a view do contrato correto
     */
    public function getContId(): int|null
    {
        return $this->contId;
    }

    public function deleteAnexo(int $id): void
    {
        // 1. Lê os dados do anexo para descobrir o nome do arquivo e a pasta (cont_id)
        $viewAnexo = new \App\adms\Models\helper\AdmsRead();
        $viewAnexo->fullRead(
            "SELECT id, cont_id, image FROM adms_contr_anexos WHERE id=:id LIMIT :limit",
            "id={$id}&limit=1"
        );

        $this->resultBd = $viewAnexo->getResult();

        if ($this->resultBd) {
            $this->contId = $this->resultBd[0]['cont_id'];
            $imageName = $this->resultBd[0]['image'];

            // 2. Apaga o registro do banco de dados
            $deleteAnexo = new \App\adms\Models\helper\AdmsDelete();
            $deleteAnexo->exeDelete("adms_contr_anexos", "WHERE id=:id", "id={$id}");

            if ($deleteAnexo->getResult()) {
                // 3. Se deletou do banco, apaga o arquivo físico (PDF) da pasta
                $directory = "app/adms/assets/arquivos/contratos/" . $this->contId . "/";
                $file = $directory . $imageName;

                if (file_exists($file)) {
                    unlink($file); // Função do PHP que destrói o arquivo fisicamente
                }

                $_SESSION['msg'] = "<p class='alert-success'>Anexo e arquivo físico apagados com sucesso!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível apagar o anexo do banco de dados!</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Anexo não encontrado!</p>";
            $this->result = false;
        }
    }
}