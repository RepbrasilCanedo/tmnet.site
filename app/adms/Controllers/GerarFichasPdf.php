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
                $_SESSION['msg'] = "<p class='alert-warning'>Não há jogos agendados para imprimir.</p>";
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

        $dompdf->stream("Fichas_4porFolha_TMNet.pdf", ["Attachment" => false]); 
    }

    private function montarHtml(): string
    {
        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <style>
                @page { margin: 5mm; }
                body { font-family: Helvetica, Arial, sans-serif; font-size: 9px; color: #000; margin: 0; padding: 0; }
                
                /* DOCAN FIX: Medida 100% blindada. 67mm * 4 = 268mm. Sobra espaço no A4! */
                .ficha-container { 
                    border: 1px solid #000; 
                    height: 67mm; 
                    margin-bottom: 2mm; 
                    page-break-inside: avoid; 
                    width: 100%; 
                    box-sizing: border-box; 
                    padding: 3px;
                    overflow: hidden;
                }
                
                .header-title { text-align: center; font-size: 12px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; }
                
                table { width: 100%; border-collapse: collapse; }
                
                td { border: 1px solid #000; padding: 3px 4px; vertical-align: middle; }
                .linha-set td { padding: 4.5px 4px; } 
                
                .col-pequena { width: 4.5%; text-align: center; font-size: 7px; }
                .col-pontos { width: 10%; } 
                .col-parciais { width: 11%; text-align: center; font-weight: bold; font-size: 8px; }
                
                /* DOCAN FIX: Forçando alinhamento ao topo para não ficar no meio! */
                td.box-resultado { 
                    width: 12%; 
                    text-align: center; 
                    vertical-align: top !important; 
                    font-size: 9px; 
                    font-weight: bold; 
                }
                .resultado-texto { margin-top: 5px; }
                
                .td-atleta { font-weight: bold; font-size: 9px; width: 50%; }
                
                /* DOCAN FIX: Assinaturas empurradas bem para baixo com padding-top */
                table.assinaturas { width: 100%; border: none; border-collapse: collapse; margin-top: 8px; }
                .assinaturas td { border: none; padding-top: 15px; text-align: center; vertical-align: bottom; }
                .linha-traco { border-top: 1px solid #000; width: 85%; margin: 0 auto; }
                .texto-assinatura { font-size: 7px; padding-top: 3px; font-weight: bold; }
            </style>
        </head>
        <body>";

        foreach ($this->jogos as $jogo) {
            
            $nomeCategoria = !empty($jogo['cat_nome']) ? $jogo['cat_nome'] : "Livre";
            $horario = !empty($jogo['horario_previsto']) ? date('H:i', strtotime($jogo['horario_previsto'])) : "--:--";
            $dataEvento = !empty($jogo['data_evento']) ? date('d/m/Y', strtotime($jogo['data_evento'])) : "";
            
            // DOCAN FIX: Removido o undercore horrível. Mostra nome ou "Ass. Árbitro"
            $arbitroText = !empty($jogo['nome_arbitro']) ? "Árbitro: " . $jogo['nome_arbitro'] : "Ass. Árbitro";
            
            $html .= "
            <div class='ficha-container'>
                <div class='header-title'>Súmula de Tênis de Mesa</div>
                
                <table>
                    <tr>
                        <td colspan='3'><strong>Evento:</strong> {$jogo['nome_torneio']}</td>
                        <td colspan='2'><strong>Data:</strong> {$dataEvento}</td>
                    </tr>
                    <tr>
                        <td width='20%'><strong>Jogo:</strong> {$jogo['id']}</td>
                        <td width='20%'><strong>Mesa:</strong> {$jogo['mesa']}</td>
                        <td width='20%'><strong>Início:</strong> {$horario}</td>
                        <td width='20%'><strong>Categoria:</strong> {$nomeCategoria}</td>
                        <td width='20%'><strong>Fase:</strong> {$jogo['fase']}</td>
                    </tr>
                </table>

                <table style='border-top: none;'>
                    <tr>
                        <td class='td-atleta'>Atleta 1: {$jogo['atleta_a']}</td>
                        <td class='td-atleta'>Atleta 2: {$jogo['atleta_b']}</td>
                    </tr>
                </table>

                <table style='border-top: none;'>
                    <tr>
                        <td rowspan='6' class='box-resultado'><div class='resultado-texto'>Resultado Final</div></td>
                        
                        <td class='col-pequena'>Serv.</td>
                        <td class='col-pequena'>A</td>
                        <td class='col-pequena'>AV1</td>
                        <td class='col-pequena'>AV2</td>
                        <td class='col-pequena'>TT</td>
                        
                        <td class='col-pontos'></td> 
                        
                        <td class='col-parciais'>Sets</td>
                        
                        <td class='col-pontos'></td> 
                        
                        <td class='col-pequena'>TT</td>
                        <td class='col-pequena'>A</td>
                        <td class='col-pequena'>AV1</td>
                        <td class='col-pequena'>AV2</td>
                        <td class='col-pequena'>Serv.</td>
                        
                        <td rowspan='6' class='box-resultado'><div class='resultado-texto'>Resultado Final</div></td>
                    </tr>";

            for ($i = 1; $i <= 5; $i++) {
                $html .= "
                    <tr class='linha-set'>
                        <td></td><td></td><td></td><td></td><td></td>
                        <td></td> 
                        <td style='text-align: center; font-weight: bold;'>{$i}º Set</td>
                        <td></td> 
                        <td></td><td></td><td></td><td></td><td></td>
                    </tr>";
            }

            $html .= "
                </table>

                <table class='assinaturas'>
                    <tr>
                        <td width='30%'>
                            <div class='linha-traco'></div>
                            <div class='texto-assinatura'>Ass. Atleta 1</div>
                        </td>
                        <td width='40%'>
                            <div class='linha-traco'></div>
                            <div class='texto-assinatura'>{$arbitroText}</div>
                        </td>
                        <td width='30%'>
                            <div class='linha-traco'></div>
                            <div class='texto-assinatura'>Ass. Atleta 2</div>
                        </td>
                    </tr>
                </table>
            </div>";
        }

        $html .= "
        </body>
        </html>";

        return $html;
    }
}