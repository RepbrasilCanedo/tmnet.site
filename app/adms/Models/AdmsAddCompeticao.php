<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsAddCompeticao
{
    private array|null $data;
    private bool $result;

    function getResult(): bool 
    { 
        return $this->result; 
    }

    // ========================================================================
    // BUSCA AS CATEGORIAS DO CLUBE PARA MOSTRAR NA TELA DE CRIAÇÃO
    // ========================================================================
    public function getCategoriasClube(): array|null
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        $list->fullRead(
            "SELECT id, nome FROM adms_categorias WHERE empresa_id = :empresa ORDER BY nome ASC", 
            "empresa={$_SESSION['emp_user']}"
        );
        return $list->getResult();
    }

    public function create(array $data): void
    {
        $this->data = $data;

        if (isset($this->data['AdmsAddComp'])) {
            unset($this->data['AdmsAddComp']);
        }

        // Separa o array de checkboxes antes de limpar os textos
        $categoriasIds = $this->data['categorias_ids'] ?? [];
        unset($this->data['categorias_ids']);

        // 1. Limpeza de dados (Apenas para strings)
        foreach ($this->data as $key => $value) {
            if (is_string($value)) {
                $this->data[$key] = trim(strip_tags($value));
            }
        }

        // 2. Validação de campos obrigatórios
        if (!empty($this->data['nome_torneio']) && !empty($this->data['data_evento'])) {
            
            // Grava os IDs das categorias separadas por vírgula (ex: "1,4,5")
            if (!empty($categoriasIds)) {
                $this->data['categorias_selecionadas'] = implode(',', $categoriasIds);
            } else {
                $this->data['categorias_selecionadas'] = null;
            }

            // 3. REGRA MULTIEMPRESA
            $this->data['empresa_id'] = $_SESSION['emp_user'];
            $this->data['created'] = date("Y-m-d H:i:s");
            
            if (isset($this->data['sistema_disputa'])) {
                $this->data['sistema_disputa'] = (int) $this->data['sistema_disputa'];
            } else {
                $this->data['sistema_disputa'] = 1;
            }

            if(!isset($this->data['status_inscricao'])){
                $this->data['status_inscricao'] = 1; // Inscrições Abertas
            }

            // 5. Executa a inserção
            $createComp = new \App\adms\Models\helper\AdmsCreate();
            $createComp->exeCreate("adms_competicoes", $this->data);

            if ($createComp->getResult()) {
                $_SESSION['msg'] = "<p class='alert-success'>Competição cadastrada com sucesso na sua empresa!</p>";
                $this->result = true;
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao salvar competição. Verifique a conexão com o banco.</p>";
                $this->result = false;
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Preencha os campos Nome e Data!</p>";
            $this->result = false;
        }
    }
}