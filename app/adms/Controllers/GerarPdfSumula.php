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

        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Súmula - {$this->detalhes['nome_torneio']}</title>
            <style>
                body { font-family: Helvetica, sans-serif; font-size: 12px; color: #333; }
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0044cc; padding-bottom: 10px; }
                .header h1 { margin: 0; color: #0044cc; font-size: 20px; }
                .info-box { background-color: #f4f4f4; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #0044cc; color: #ffffff; padding: 8px; text-align: center; border: 1px solid #ddd; }
                td { padding: 8px; text-align: center; border: 1px solid #ddd; }
                .vencedor { color: #0044cc; font-weight: bold; }
                
                /* Estilos do Pódio */
                .podio-section { margin-top: 30px; page-break-inside: avoid; }
                .podio-titulo { text-align: center; color: #0044cc; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 15px;}
                .podio-box { border: 1px solid #999; margin-bottom: 15px; border-radius: 5px; overflow: hidden; }
                .podio-div-nome { background: #333; color: #fff; padding: 8px; font-weight: bold; text-align: center; margin: 0; }
                .podio-table { border: none; margin: 0; }
                .podio-table td { border: none; padding: 6px 10px; text-align: left; width: 50%; font-size: 13px;}
                .ouro { color: #d4af37; }
                .prata { color: #8e8d8d; }
                .bronze { color: #cd7f32; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>TMNet - Súmula Oficial de Resultados</h1>
            </div>

            <div class='info-box'>
                <strong>Torneio:</strong> {$this->detalhes['nome_torneio']}<br>
                <strong>Data:</strong> {$dataEvento} | <strong>Local:</strong> {$this->detalhes['local_evento']}<br>
                <strong>Categoria CBTM:</strong> {$this->detalhes['categoria_cbtm']} | <strong>Fator (Peso):</strong> x{$peso}
            </div>

            <h2>Resultados das Partidas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Fase</th>
                        <th>Categoria</th>
                        <th>Atleta A</th>
                        <th>Placar</th>
                        <th>Atleta B</th>
                        <th>Vencedor</th>
                        <th>Pts</th>
                    </tr>
                </thead>
                <tbody>";

        if (!empty($this->partidas)) {
            foreach ($this->partidas as $partida) {
                $atletaA = ($partida['vencedor_id'] == $partida['atleta_a_id']) ? "<span class='vencedor'>{$partida['atleta_a']}</span>" : $partida['atleta_a'];
                $atletaB = ($partida['vencedor_id'] == $partida['atleta_b_id']) ? "<span class='vencedor'>{$partida['atleta_b']}</span>" : $partida['atleta_b'];
                
                // Exibe a Divisão e o Gênero na tabela de partidas
                $tipoGenero = $partida['tipo_genero'] ?? 1;
                $genDisplay = ($tipoGenero == 2) ? (($partida['genero_partida'] == 'F') ? ' (Fem)' : ' (Masc)') : '';
                $divisao = ($partida['div_nome'] ?? 'Livre') . $genDisplay;
                
                $html .= "
                    <tr>
                        <td>{$partida['fase']}</td>
                        <td style='font-size: 10px; color: #666;'>{$divisao}</td>
                        <td>{$atletaA}</td>
                        <td><strong>{$partida['sets_atleta_a']} x {$partida['sets_atleta_b']}</strong></td>
                        <td>{$atletaB}</td>
                        <td>{$partida['vencedor']}</td>
                        <td>+{$partida['pontos_ganhos']}</td>
                    </tr>";
            }
        } else {
            $html .= "<tr><td colspan='7'>Nenhuma partida registrada até o momento.</td></tr>";
        }

        $html .= "
                </tbody>
            </table>";

        // =========================================================
        // QUADRO DE HONRA (PÓDIO)
        // =========================================================
        if (!empty($this->podios)) {
            $html .= "
            <div class='podio-section'>
                <h2 class='podio-titulo'>Quadro de Honra (Resultados Finais)</h2>";

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
                            <td><strong class='ouro'>1º Lugar (Ouro):</strong> {$campeao}</td>
                            <td><strong class='prata'>2º Lugar (Prata):</strong> {$vice}</td>
                        </tr>
                        <tr>
                            <td><strong class='bronze'>3º Lugar (Bronze):</strong> {$terceiro1}</td>
                            <td><strong class='bronze'>3º Lugar (Bronze):</strong> {$terceiro2}</td>
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
            <div style='margin-top: 50px; text-align: center; page-break-inside: avoid;'>
                <p>_______________________________________________________</p>
                <p>Assinatura do Árbitro Geral / Organização</p>
            </div>
        </body>
        </html>";

        return $html;
    }
}