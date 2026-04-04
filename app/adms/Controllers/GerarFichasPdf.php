<?php

namespace App\adms\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerarFichasPdf
{
    private array|null $jogos;

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (!empty($id)) {
            $fichas = new \App\adms\Models\AdmsGerarFichasPdf();
            $fichas->buscarJogosAgendados($id);
            $this->jogos = $fichas->getResult();

            if (!empty($this->jogos)) {
                $this->gerarPdf();
            } else {
                $_SESSION['msg'] = "<p class='alert-warning'>Não há jogos agendados para imprimir. Gere a agenda primeiro!</p>";
                header("Location: " . URLADM . "view-competicao/index/{$id}");
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

        $nomeArquivo = "Fichas_de_Mesa_Torneio.pdf";
        $dompdf->stream($nomeArquivo, ["Attachment" => false]); // false abre na tela, true faz o download direto
    }

    private function montarHtml(): string
    {
        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <style>
                body { font-family: Helvetica, sans-serif; font-size: 11px; color: #000; margin: 0; padding: 0; }
                
                .ficha-box { border: 2px solid #000; border-radius: 5px; margin-bottom: 25px; padding: 10px; page-break-inside: avoid; }
                
                .ficha-header { display: table; width: 100%; border-bottom: 1px solid #000; padding-bottom: 5px; margin-bottom: 10px; font-weight: bold; }
                .header-left { display: table-cell; text-align: left; width: 30%; }
                .header-center { display: table-cell; text-align: center; width: 40%; font-size: 14px; }
                .header-right { display: table-cell; text-align: right; width: 30%; font-size: 10px; }
                
                table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
                th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                th { background-color: #f0f0f0; }
                
                .atleta-nome { text-align: left; width: 35%; font-weight: bold; font-size: 13px; }
                .set-box { width: 8%; }
                
                .signatures { display: table; width: 100%; margin-top: 15px; font-size: 10px; }
                .sig-box { display: table-cell; text-align: center; width: 25%; }
                .sig-line { border-top: 1px solid #000; width: 80%; margin: 0 auto; padding-top: 3px; }
            </style>
        </head>
        <body>";

        foreach ($this->jogos as $jogo) {
            
            // CORREÇÃO: Usando cat_nome agora!
            $nomeCategoria = !empty($jogo['cat_nome']) ? "{$jogo['cat_nome']} - " : "";
            $horarioFormatado = !empty($jogo['horario_previsto']) ? date('H:i', strtotime($jogo['horario_previsto'])) : "A definir";

            $html .= "
            <div class='ficha-box'>
                <div class='ficha-header'>
                    <div class='header-left'>{$jogo['nome_torneio']}</div>
                    <div class='header-center'>MESA {$jogo['mesa']} <br><small style='font-size:10px; font-weight:normal;'>{$nomeCategoria}{$jogo['fase']}</small></div>
                    <div class='header-right'>Jogo #{$jogo['id']} <br> Horário: {$horarioFormatado}</div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th class='atleta-nome'>Atletas</th>
                            <th class='set-box'>Set 1</th>
                            <th class='set-box'>Set 2</th>
                            <th class='set-box'>Set 3</th>
                            <th class='set-box'>Set 4</th>
                            <th class='set-box'>Set 5</th>
                            <th class='set-box' style='font-size: 9px;'>Amarelo</th>
                            <th class='set-box' style='font-size: 9px;'>Vermelho</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class='atleta-nome'>A) {$jogo['atleta_a']}</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                        <tr>
                            <td class='atleta-nome'>B) {$jogo['atleta_b']}</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                        </tr>
                    </tbody>
                </table>

                <div style='margin-bottom: 15px;'>
                    <strong>Vencedor:</strong> _________________________________________________ <strong>Placar Final (Sets):</strong> _____ x _____
                </div>

                <div class='signatures'>
                    <div class='sig-box'>
                        <div class='sig-line'>Assinatura Atleta A</div>
                    </div>
                    <div class='sig-box'>
                        <div class='sig-line'>Assinatura Atleta B</div>
                    </div>
                    <div class='sig-box'>
                        <div class='sig-line'>Assinatura Árbitro</div>
                    </div>
                </div>
            </div>";
        }

        $html .= "
        </body>
        </html>";

        return $html;
    }
}