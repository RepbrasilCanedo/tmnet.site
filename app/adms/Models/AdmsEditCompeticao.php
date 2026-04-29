<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditCompeticao
{
    private array|null $result;
    private bool $status = false;

    public function getResult(): array|null { return $this->result; }
    public function getStatus(): bool { return $this->status; }

    public function viewCompeticao(int $id): void
    {
        $view = new \App\adms\Models\helper\AdmsRead();
        $view->fullRead("SELECT * FROM adms_competicoes WHERE id=:id AND empresa_id=:empresa LIMIT 1", "id={$id}&empresa={$_SESSION['emp_user']}");
        $this->result = $view->getResult();
    }

    public function listarCategorias(): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT id, nome, pontuacao_maxima FROM adms_categorias WHERE empresa_id = :empresa ORDER BY pontuacao_maxima DESC, nome ASC", "empresa={$_SESSION['emp_user']}");
        return $read->getResult();
    }

    public function update(array $dados): void
    {
        $id = (int)$dados['id'];
        unset($dados['id'], $dados['SendEditComp']);

        // ========================================================================
        // DOCAN ENGINE: Tratamento do Upload do PDF do Regulamento
        // ========================================================================
        $arquivoPdf = $dados['regulamento'] ?? null;
        unset($dados['regulamento']); // Tira do array para não quebrar o Update padrão

        if (!empty($arquivoPdf['name'])) {
            // Verifica se é realmente um PDF
            if ($arquivoPdf['type'] === 'application/pdf') {
                
                $slug = new \App\adms\Models\helper\AdmsSlug();
                $nomeOriginalSemExtensao = pathinfo($arquivoPdf['name'], PATHINFO_FILENAME);
                $nomeArquivoPadronizado = $slug->slug($nomeOriginalSemExtensao) . '.pdf';
                
                $diretorio = "app/adms/assets/arquivos/competicao/" . $id . "/";
                
                // Cria a pasta se ela não existir
                if (!file_exists($diretorio)) {
                    mkdir($diretorio, 0755, true);
                }
                
                // Move o arquivo para o servidor
                if (move_uploaded_file($arquivoPdf['tmp_name'], $diretorio . $nomeArquivoPadronizado)) {
                    $dados['regulamento'] = $nomeArquivoPadronizado; // Adiciona ao array para ir para o Banco de Dados
                } else {
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao fazer o upload do PDF no servidor.</p>";
                    $this->status = false;
                    return;
                }
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: O regulamento deve ser estritamente um arquivo formato PDF.</p>";
                $this->status = false;
                return;
            }
        }

        if (isset($dados['categorias_selecionadas'])) {
            $dados['categorias_selecionadas'] = implode(',', $dados['categorias_selecionadas']);
        } else {
            $dados['categorias_selecionadas'] = null;
        }

        $camposPontuacao = ['pts_campeao', 'pts_vice', 'pts_terceiro', 'pts_quartas', 'pts_vitoria_jogo', 'pts_derrota_jogo', 'pts_participacao'];
        foreach($camposPontuacao as $cp){
            $dados[$cp] = empty($dados[$cp]) ? 0 : (int)$dados[$cp];
        }

        // ========================================================================
        // DOCAN FIX: Tratamento dos Valores Financeiros (Geral, Sócio e Estudante)
        // ========================================================================
        $camposFinanceiros = [
            'valor_uma_categoria', 'valor_duas_categorias', 
            'valor_uma_socio', 'valor_duas_socio', 
            'valor_uma_estudante', 'valor_duas_estudante'
        ];

        foreach ($camposFinanceiros as $campo) {
            if (isset($dados[$campo])) {
                $dados[$campo] = empty($dados[$campo]) ? 0.00 : str_replace(',', '.', $dados[$campo]);
            }
        }

        $dados['modified'] = date("Y-m-d H:i:s");

        $up = new \App\adms\Models\helper\AdmsUpdate();
        $up->exeUpdate("adms_competicoes", $dados, "WHERE id = :id AND empresa_id = :empresa", "id={$id}&empresa={$_SESSION['emp_user']}");

        // Se o upload funcionou ou se houveram outras atualizações, confirma sucesso.
        if ($up->getResult() || !empty($arquivoPdf['name'])) {
            $_SESSION['msg'] = "<p class='alert-success'>Sucesso: Competição atualizada com sucesso!</p>";
            $this->status = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível atualizar a competição ou não houve alterações.</p>";
            $this->status = false;
        }
    }
}