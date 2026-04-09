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

        $categoriasIds = $this->data['categorias_ids'] ?? [];
        unset($this->data['categorias_ids']);

        foreach ($this->data as $key => $value) {
            if (is_string($value)) {
                $this->data[$key] = trim(strip_tags($value));
            }
        }

        if (!empty($this->data['nome_torneio']) && !empty($this->data['data_evento'])) {
            
            if (!empty($categoriasIds)) {
                $this->data['categorias_selecionadas'] = implode(',', $categoriasIds);
            } else {
                $this->data['categorias_selecionadas'] = null;
            }

            $this->data['empresa_id'] = $_SESSION['emp_user'];
            $this->data['created'] = date("Y-m-d H:i:s");
            
            if (isset($this->data['sistema_disputa'])) {
                $this->data['sistema_disputa'] = (int) $this->data['sistema_disputa'];
            } else {
                $this->data['sistema_disputa'] = 1;
            }

            if(!isset($this->data['status_inscricao'])){
                $this->data['status_inscricao'] = 1; 
            }
            
            // DOCAN FIX: Força os campos de pontuação vazios a virarem ZERO.
            $camposPontuacao = ['pts_campeao', 'pts_vice', 'pts_terceiro', 'pts_quartas', 'pts_vitoria_jogo', 'pts_derrota_jogo', 'pts_participacao'];
            foreach($camposPontuacao as $cp){
                $this->data[$cp] = empty($this->data[$cp]) ? 0 : (int)$this->data[$cp];
            }

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