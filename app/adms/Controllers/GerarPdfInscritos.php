<?php

namespace App\adms\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class GerarPdfInscritos
{
    private array|null $inscritos;
    private array|null $torneioDetalhes;

    public function index(int|string|null $id = null): void
    {
        $id = (int) $id;

        if (empty($_SESSION['user_id']) || $_SESSION['adms_access_level_id'] >= 14) {
            die("Acesso Negado.");
        }

        if (!empty($id)) {
            
            // Busca o nome do torneio para o cabeçalho
            $read = new \App\adms\Models\helper\AdmsRead();
            $read->fullRead("SELECT nome_torneio, data_evento FROM adms_competicoes WHERE id = :id AND empresa_id = :empresa LIMIT 1", "id={$id}&empresa={$_SESSION['emp_user']}");
            $this->torneioDetalhes = $read->getResult()[0] ?? null;

            if (!$this->torneioDetalhes) {
                die("Torneio não encontrado.");
            }

            // Reutiliza a sua Model brilhante para pegar os inscritos!
            $modelInscritos = new \App\adms\Models\AdmsGerirInscricoes();
            $modelInscritos->listarInscritos($id);
            $this->inscritos = $modelInscritos->getResultBd() ?: [];

            $this->gerarPdf();
            
        } else {
            header("Location: " . URLADM . "gerir-inscricoes/index");
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

        $nomeArquivo = "Inscritos_" . str_replace(' ', '_', $this->torneioDetalhes['nome_torneio']) . ".pdf";
        $dompdf->stream($nomeArquivo, ["Attachment" => false]); 
    }

    private function montarHtml(): string
    {
        $dataEvento = date('d/m/Y', strtotime($this->torneioDetalhes['data_evento']));
        $totalAtletas = count($this->inscritos);
        
        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Relatório de Inscritos</title>
            <style>
                @page { margin: 10mm; }
                body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; }
                
                .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0044cc; padding-bottom: 10px; }
                .header h1 { margin: 0; color: #0044cc; font-size: 18px; text-transform: uppercase; }
                .header p { margin: 5px 0 0 0; font-size: 12px; color: #555; }
                
                .info-box { background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; border-radius: 4px; margin-bottom: 15px; font-size: 12px; }
                
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th { background-color: #333; color: #fff; padding: 8px 5px; text-align: left; font-size: 11px; border: 1px solid #222; }
                td { padding: 8px 5px; border: 1px solid #ddd; font-size: 10px; vertical-align: middle; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                
                .status-1 { color: #856404; font-weight: bold; } /* Aguardando */
                .status-2 { color: #155724; font-weight: bold; } /* Pago */
                .status-3 { color: #0c5460; font-weight: bold; } /* Isento */
                
                .valor { text-align: right; font-weight: bold; }
                
                footer { position: fixed; bottom: -5mm; left: 0px; right: 0px; text-align: center; font-size: 8px; color: #777; border-top: 1px solid #eee; padding-top: 5px; }
            </style>
        </head>
        <body>
            <footer>Gerado por TMNet | &copy; " . date('Y') . " RepBrasil Tecnologia - Todos os direitos reservados</footer>

            <div class='header'>
                <h1>Relatório de Inscrições e Financeiro</h1>
                <p>{$this->torneioDetalhes['nome_torneio']}</p>
            </div>

            <div class='info-box'>
                <strong>Data do Evento:</strong> {$dataEvento} &nbsp;|&nbsp; 
                <strong>Total de Inscritos:</strong> {$totalAtletas} atleta(s)
            </div>

            <table>
                <thead>
                    <tr>
                        <th width='5%'>Nº</th>
                        <th width='25%'>Atleta</th>
                        <th width='15%'>Telefone</th>
                        <th width='30%'>Categorias</th>
                        <th width='15%' style='text-align: right;'>Valor (R$)</th>
                        <th width='10%' style='text-align: center;'>Status</th>
                    </tr>
                </thead>
                <tbody>";

        $totalArrecadado = 0;
        $totalAguardando = 0;
        $contador = 1;

        if (!empty($this->inscritos)) {
            foreach ($this->inscritos as $ins) {
                
                // Trata a string da categoria removendo os <br> do HTML para a versão PDF
                $categoriasLimpo = str_replace(' <br> ', ', ', $ins['categorias_str']);
                
                // Lógica de Status
                if ($ins['status_pagamento_id'] == 2) {
                    $statusHtml = "<span class='status-2'>Pago</span>";
                    $totalArrecadado += $ins['valor_total'];
                } elseif ($ins['status_pagamento_id'] == 1) {
                    $statusHtml = "<span class='status-1'>Aguardando</span>";
                    $totalAguardando += $ins['valor_total'];
                } else {
                    $statusHtml = "<span class='status-3'>Isento</span>";
                }

                $valorFmt = number_format($ins['valor_total'], 2, ',', '.');
                $tipoInsc = $ins['tipo_inscricao'] == 'Geral' ? '' : " <span style='color:#777; font-size:9px;'>({$ins['tipo_inscricao']})</span>";

                $html .= "
                    <tr>
                        <td style='text-align: center;'>{$contador}</td>
                        <td><strong>{$ins['atleta']}</strong>{$tipoInsc}</td>
                        <td>{$ins['telefone_display']}</td>
                        <td>{$categoriasLimpo}</td>
                        <td class='valor'>{$valorFmt}</td>
                        <td style='text-align: center;'>{$statusHtml}</td>
                    </tr>";
                $contador++;
            }
        } else {
            $html .= "<tr><td colspan='6' style='text-align:center; padding: 20px;'>Nenhum inscrito até o momento.</td></tr>";
        }

        // Rodapé da Tabela com o Resumo Financeiro
        $arrecadadoFmt = number_format($totalArrecadado, 2, ',', '.');
        $aguardandoFmt = number_format($totalAguardando, 2, ',', '.');
        $html .= "
                </tbody>
            </table>

            <div style='margin-top: 20px; border-top: 1px solid #333; padding-top: 10px; text-align: right; font-size: 13px;'>
                <p style='margin: 3px 0; color: #856404;'>Total Aguardando Pagamento: <strong>R$ {$aguardandoFmt}</strong></p>
                <p style='margin: 3px 0; color: #155724; font-size: 15px;'>Total Recebido (Pagos): <strong>R$ {$arrecadadoFmt}</strong></p>
            </div>

        </body>
        </html>";

        return $html;
    }
}