<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsSorteioGrupos
{
    private bool $result = false;
    private int $tipoCompeticao = 1;

    function getResult(): bool { return $this->result; }
    function getTipoCompeticao(): int { return $this->tipoCompeticao; }

    public function obterDetalhesCompeticao(int $compId): array
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead("SELECT status_inscricao, sistema_disputa, tipo_competicao, tipo_genero FROM adms_competicoes WHERE id = :id LIMIT 1", "id={$compId}");
        if ($read->getResult()) {
            $this->tipoCompeticao = (int)$read->getResult()[0]['tipo_competicao'];
            return $read->getResult()[0];
        }
        return ['status_inscricao' => 1, 'sistema_disputa' => 1, 'tipo_competicao' => 1, 'tipo_genero' => 1];
    }

    public function listarAtletasRanking(int $compId): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $detalhes = $this->obterDetalhesCompeticao($compId);
        $tipoGenero = $detalhes['tipo_genero'] ?? 1;

        $ordemExtra = ($tipoGenero == 2) ? "u.genero ASC," : "";

        // Traz as inscrições separadas por CATEGORIA e depois ordenadas pelo Ranking
        $read->fullRead(
            "SELECT u.id, u.name, u.apelido, u.pontuacao_ranking, u.genero, i.id as inscricao_id, c.id as cat_id, c.nome as cat_nome
             FROM adms_users u
             INNER JOIN adms_inscricoes i ON i.adms_user_id = u.id
             INNER JOIN adms_categorias c ON c.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id
             ORDER BY c.nome ASC, {$ordemExtra} u.pontuacao_ranking DESC", 
            "comp_id={$compId}"
        );
        
        return $read->getResult();
    }

    public function gerarSorteio(array $dados): void
    {
        $compId = (int)$dados['adms_competicao_id'];
        $detalhes = $this->obterDetalhesCompeticao($compId);

        if ($detalhes['status_inscricao'] == 1) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Encerre as inscrições na Súmula antes de realizar o sorteio!</p>";
            $this->result = false;
            return;
        }

        if (empty($dados['inscricoes_ids']) || count($dados['inscricoes_ids']) < 2) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Selecione atletas suficientes!</p>";
            $this->result = false;
            return;
        }

        $sistemaDisputa = (int)$detalhes['sistema_disputa']; 
        $tipoCompeticao = (int)$detalhes['tipo_competicao'];
        $tipoGenero = (int)($detalhes['tipo_genero'] ?? 1);
        
        $inscricoesMarcadasStr = implode(',', array_map('intval', $dados['inscricoes_ids']));
        
        $read = new \App\adms\Models\helper\AdmsRead();
        // Busca as inscrições marcadas com suas respectivas Categorias
        $read->fullRead(
            "SELECT i.id, i.adms_user_id, i.adms_categoria_id, u.pontuacao_ranking, u.genero 
             FROM adms_inscricoes i 
             INNER JOIN adms_users u ON u.id = i.adms_user_id 
             WHERE i.id IN ($inscricoesMarcadasStr)
             ORDER BY u.pontuacao_ranking DESC"
        );
        $inscricoesAtuais = $read->getResult();

        // Limpa os sorteios antigos
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_partidas", "WHERE adms_competicao_id = :comp_id", "comp_id={$compId}");
        
        $updateInscricao = new \App\adms\Models\helper\AdmsUpdate();
        $updateInscricao->exeUpdate("adms_inscricoes", ['grupo' => NULL], "WHERE adms_competicao_id = :comp_id", "comp_id={$compId}");

        $create = new \App\adms\Models\helper\AdmsCreate();
        $potesDeSorteio = [];
        
        // ====================================================================
        // AGRUPA POR: CATEGORIA -> GÊNERO
        // ====================================================================
        foreach ($inscricoesAtuais as $insc) {
            $catId = $insc['adms_categoria_id'] ?? 0;
            $gen = ($tipoGenero == 2) ? ($insc['genero'] ?? 'M') : 'X'; // X = Misto
            
            $potesDeSorteio[$catId][$gen][] = $insc;
        }

        $qtdGruposForm = (int)($dados['qtd_grupos'] ?? 2);

        // Loop pelas Categorias
        foreach ($potesDeSorteio as $categoriaId => $potesPorGenero) {
            
            // Loop pelos Gêneros dentro da Categoria
            foreach ($potesPorGenero as $generoId => $listaAtletas) {
                
                // Se o torneio for Livre (Aleatório), embaralha os atletas desta categoria, ignorando ranking
                if ($tipoCompeticao == 1) {
                    shuffle($listaAtletas);
                }

                $numAtletas = count($listaAtletas);
                if ($numAtletas < 2) continue; // Pula se não houver atletas suficientes

                if ($sistemaDisputa == 2) {
                    // TODOS CONTRA TODOS
                    foreach ($listaAtletas as $atleta) {
                        $updateInscricao->exeUpdate("adms_inscricoes", ['grupo' => 'Único'], "WHERE id = :id", "id={$atleta['id']}");
                    }
                    for ($i = 0; $i < $numAtletas - 1; $i++) {
                        for ($j = $i + 1; $j < $numAtletas; $j++) {
                            $create->exeCreate("adms_partidas", [
                                'adms_competicao_id' => $compId, 
                                'adms_categoria_id' => $categoriaId,
                                'genero_partida' => $generoId,
                                'fase' => 'Classificatória',
                                'atleta_a_id' => $listaAtletas[$i]['adms_user_id'], 
                                'atleta_b_id' => $listaAtletas[$j]['adms_user_id'],
                                'created' => date("Y-m-d H:i:s")
                            ]);
                        }
                    }
                } else {
                    // SISTEMA DE GRUPOS (ALGORITMO SNAKE)
                    $qtdGruposReal = $qtdGruposForm;
                    $maxGruposPermitidos = (int)floor($numAtletas / 2);
                    if ($qtdGruposReal > $maxGruposPermitidos) $qtdGruposReal = $maxGruposPermitidos;
                    if ($qtdGruposReal < 1) $qtdGruposReal = 1; 

                    $letrasGrupos = range('A', 'Z');
                    $direcao = 1; $indiceGrupo = 0;
                    $atletasPorGrupo = [];

                    foreach ($listaAtletas as $atleta) {
                        $grupoAtual = $letrasGrupos[$indiceGrupo];
                        
                        $prefixoGen = ($tipoGenero == 2) ? $generoId . '-' : '';
                        $grupoFinal = $prefixoGen . $grupoAtual;

                        $updateInscricao->exeUpdate("adms_inscricoes", ['grupo' => $grupoFinal], "WHERE id = :id", "id={$atleta['id']}");
                        $atletasPorGrupo[$grupoFinal][] = $atleta;

                        // Lógica Snake (0, 1, 2, 2, 1, 0...)
                        $indiceGrupo += $direcao;
                        if ($indiceGrupo >= $qtdGruposReal) { $indiceGrupo = $qtdGruposReal - 1; $direcao = -1; }
                        elseif ($indiceGrupo < 0) { $indiceGrupo = 0; $direcao = 1; }
                    }

                    // Gera as partidas de dentro do grupo
                    foreach ($atletasPorGrupo as $nomeGrupo => $atletasDoGrupo) {
                        $num = count($atletasDoGrupo);
                        for ($i = 0; $i < $num - 1; $i++) {
                            for ($j = $i + 1; $j < $num; $j++) {
                                $create->exeCreate("adms_partidas", [
                                    'adms_competicao_id' => $compId, 
                                    'adms_categoria_id' => $categoriaId,
                                    'genero_partida' => $generoId,
                                    'fase' => "Grupo " . str_replace(['M-', 'F-'], '', $nomeGrupo), 
                                    'atleta_a_id' => $atletasDoGrupo[$i]['adms_user_id'], 
                                    'atleta_b_id' => $atletasDoGrupo[$j]['adms_user_id'],
                                    'created' => date("Y-m-d H:i:s")
                                ]);
                            }
                        }
                    }
                }
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Chaveamento otimizado e separado por categorias gerado com sucesso!</p>";
        $this->result = true;
    }
    
    public function listarGruposGerados(int $compId): array|null
    {
        $read = new \App\adms\Models\helper\AdmsRead();
        $read->fullRead(
            "SELECT i.grupo, u.name, u.pontuacao_ranking, u.genero, c.nome as cat_nome, comp.tipo_competicao, comp.tipo_genero
             FROM adms_inscricoes i
             INNER JOIN adms_users u ON u.id = i.adms_user_id
             INNER JOIN adms_competicoes comp ON comp.id = i.adms_competicao_id
             INNER JOIN adms_categorias c ON c.id = i.adms_categoria_id
             WHERE i.adms_competicao_id = :comp_id AND i.grupo IS NOT NULL
             ORDER BY c.nome ASC, i.grupo ASC, u.pontuacao_ranking DESC", 
            "comp_id={$compId}"
        );
        return $read->getResult();
    }
}