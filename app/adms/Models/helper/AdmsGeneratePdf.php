<?php

namespace App\adms\Models\helper;
// referencia o namespace Dompdf
use Dompdf\Dompdf;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe genérica para gerar PDF
 *
 * @author Daniel Canedo
 */
class AdmsGeneratePdf
{
    /** @var string|null $data Receber as informações para o PDF */
    
    private string|null $data;

    /**
     * Metodo recebe o conteúdo para o PDF
     * @param string|null $data
     * @return void
     */
    public function generatePdf(string|null $data_pdf): void
    {
        
        // instanciar e usar a classe dompdf
        $dompdf = new Dompdf();

        // Carreha o Html
        $dompdf->loadHtml($data_pdf);

        // (Opcional) Configure o tamanho e a orientação do papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar o HTML como PDF
        $dompdf->render();

        // Envie o PDF gerado para o navegador
        $dompdf->stream();
    }
}
