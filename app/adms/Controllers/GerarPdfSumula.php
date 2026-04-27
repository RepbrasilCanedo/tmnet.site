<?php

namespace App\adms\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerarPdfSumula
{
    private array|null $detalhes;
    private array|null $partidas;
    private array $podios = [];

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (!empty($id)) {
            $viewComp = new \App\adms\Models\AdmsViewCompeticao();
            $viewComp->viewCompeticao($id);
            $resultado = $viewComp->getResult();

            if (!empty($resultado['detalhes'])) {
                $this->detalhes = $resultado['detalhes'];
                $this->partidas = $resultado['partidas'] ?? [];
                $this->podios = $resultado['podios'] ?? [];
                
                $this->gerarPdf();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Competição não encontrada!</p>";
                header("Location: " . URLADM . "list-competicoes/index");
            }
        } else {
            header("Location: " . URLADM . "list-competicoes/index");
        }
    }

    private function gerarPdf(): void
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $html = $this->montarHtml();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $nomeArquivo = "Sumula_" . str_replace(' ', '_', $this->detalhes['nome_torneio']) . ".pdf";
        $dompdf->stream($nomeArquivo, ["Attachment" => false]);
    }

    private function montarHtml(): string
    {
        $dataEvento = date('d/m/Y', strtotime($this->detalhes['data_evento']));
        $peso = number_format($this->detalhes['fator_multiplicador'], 2);

        // ========================================================================
        // DOCAN LOGIC: AGRUPAMENTO DE PARTIDAS POR CATEGORIA/DIVISÃO
        // ========================================================================
        $partidasAgrupadas = [];
        if (!empty($this->partidas)) {
            foreach ($this->partidas as $partida) {
                $tipoGenero = $partida['tipo_genero'] ?? 1;
                $genDisplay = ($tipoGenero == 2) ? (($partida['genero_partida'] == 'F') ? ' (Fem)' : ' (Masc)') : '';
                $divisao = ($partida['cat_nome'] ?? 'Livre') . $genDisplay;
                
                $partidasAgrupadas[$divisao][] = $partida;
            }
        }

        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Súmula - {$this->detalhes['nome_torneio']}</title>
            <style>
                body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 0; padding: 0; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0044cc; padding-bottom: 10px; }
                .header h1 { margin: 0; color: #0044cc; font-size: 18px; text-transform: uppercase; }
                .info-box { background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px; }
                
                .categoria-header { background-color: #333; color: #fff; padding: 6px 10px; margin-top: 25px; font-size: 13px; border-radius: 3px; }
                
                table { width: 100%; border-collapse: collapse; margin-top: 5px; page-break-inside: auto; }
                th { background-color: #0044cc; color: #ffffff; padding: 6px; text-align: center; border: 1px solid #ddd; font-size: 10px; }
                td { padding: 6px; text-align: center; border: 1px solid #ddd; font-size: 10px; }
                tr:nth-child(even) { background-color: #fdfdfd; }
                
                .vencedor { color: #0044cc; font-weight: bold; }
                
                .podio-section { margin-top: 40px; page-break-before: auto; }
                .podio-titulo { text-align: center; color: #0044cc; border-bottom: 2px solid #0044cc; padding-bottom: 5px; margin-bottom: 15px; text-transform: uppercase; }
                .podio-box { border: 1px solid #999; margin-bottom: 15px; border-radius: 5px; overflow: hidden; page-break-inside: avoid; }
                .podio-div-nome { background: #444; color: #fff; padding: 8px; font-weight: bold; text-align: center; margin: 0; font-size: 12px; }
                .podio-table { border: none; margin: 0; }
                .podio-table td { border: none; padding: 8px 15px; text-align: left; width: 50%; font-size: 12px;}
                
                .ouro { color: #b8860b; font-weight: bold; }
                .prata { color: #707070; font-weight: bold; }
                .bronze { color: #8b4513; font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>TMNet - Relatório Oficial de Resultados</h1>
            </div>

            <div class='info-box'>
                <strong>Torneio:</strong> {$this->detalhes['nome_torneio']}<br>
                <strong>Data:</strong> {$dataEvento} | <strong>Local:</strong> {$this->detalhes['local_evento']}<br>
                <strong>Peso do Evento:</strong> x{$peso} | <strong>Categoria CBTM:</strong> {$this->detalhes['categoria_cbtm']}
            </div>

            <h2>Detalhamento das Partidas</h2>";

        if (!empty($partidasAgrupadas)) {
            foreach ($partidasAgrupadas as $nomeDivisao => $listaPartidas) {
                $html .= "
                <div class='categoria-header'>Divisão: {$nomeDivisao}</div>
                <table>
                    <thead>
                        <tr>
                            <th width='20%'>Fase</th>
                            <th width='25%'>Atleta A</th>
                            <th width='10%'>Placar</th>
                            <th width='25%'>Atleta B</th>
                            <th width='20%'>Vencedor</th>
                        </tr>
                    </thead>
                    <tbody>";

                foreach ($listaPartidas as $partida) {
                    $atletaA = ($partida['vencedor_id'] == $partida['atleta_a_id']) ? "<span class='vencedor'>{$partida['atleta_a']}</span>" : $partida['atleta_a'];
                    $atletaB = ($partida['vencedor_id'] == $partida['atleta_b_id']) ? "<span class='vencedor'>{$partida['atleta_b']}</span>" : $partida['atleta_b'];
                    
                    $html .= "
                        <tr>
                            <td><small>{$partida['fase']}</small></td>
                            <td>{$atletaA}</td>
                            <td><strong>{$partida['sets_atleta_a']} x {$partida['sets_atleta_b']}</strong></td>
                            <td>{$atletaB}</td>
                            <td class='vencedor'>{$partida['vencedor']}</td>
                        </tr>";
                }
                $html .= "</tbody></table>";
            }
        } else {
            $html .= "<p style='text-align:center;'>Nenhuma partida registrada até o momento.</p>";
        }

        // =========================================================
        // QUADRO DE HONRA (PÓDIO) - MANTIDO NO FINAL
        // =========================================================
        if (!empty($this->podios)) {
            $html .= "
            <div class='podio-section'>
                <h2 class='podio-titulo'>Quadro de Honra / Campeões</h2>";

            foreach ($this->podios as $chave => $dadosPodio) {
                $campeao = $dadosPodio['campeao'] ?? 'A definir';
                $vice = $dadosPodio['vice'] ?? 'A definir';
                $terceiro1 = $dadosPodio['terceiros'][0] ?? '-';
                $terceiro2 = $dadosPodio['terceiros'][1] ?? '-';

                $html .= "
                <div class='podio-box'>
                    <h3 class='podio-div-nome'>{$dadosPodio['titulo']}</h3>
                    <table class='podio-table'>
                        <tr>
                            <td><span class='ouro'>🥇 1º Lugar:</span> {$campeao}</td>
                            <td><span class='prata'>🥈 2º Lugar:</span> {$vice}</td>
                        </tr>
                        <tr>
                            <td><span class='bronze'>🥉 3º Lugar:</span> {$terceiro1}</td>
                            <td><span class='bronze'>🥉 3º Lugar:</span> {$terceiro2}</td>
                        </tr>
                    </table>
                </div>";
            }
            $html .= "</div>";
        }

        // =========================================================
        // ASSINATURAS
        // =========================================================
        $html .= "
            <div style='margin-top: 60px; text-align: center; page-break-inside: avoid;'>
                <p>_______________________________________________________</p>
                <p style='font-size: 12px; font-weight: bold;'>Assinatura da Organização / Árbitro Geral</p>
                <p style='font-size: 10px; color: #777;'>Documento gerado automaticamente pela Plataforma TMNet em " . date('d/m/Y H:i') . "</p>
            </div>
        </body>
        </html>";

        return $html;
    }
}