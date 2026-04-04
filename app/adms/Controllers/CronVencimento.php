<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller responsável pela execução automática (Cron Job)
 * Envia relatório semanal de vencimentos por e-mail
 */
class CronVencimento
{
    private array|null $listaVencimentos;

    public function index(): void
    {
        // 1. Instancia a Model para buscar os dados
        $model = new \App\adms\Models\AdmsCronVencimento();
        $this->listaVencimentos = $model->buscarVencimentosSemana();

        // 2. Verifica se existem equipamentos vencendo na semana
        if ($this->listaVencimentos) {
            $this->processarEnvio();
            echo "Cron executada: Relatório enviado para o e-mail cadastrado.";
        } else {
            echo "Cron executada: Nenhum equipamento vence nos próximos 7 dias.";
        }
    }

    /**
     * Monta o layout HTML e dispara o e-mail através da Helper
     */
    private function processarEnvio(): void
    {
        // Estilização do corpo do e-mail (Melhoria de UI)
        $corpo = "<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>";
        $corpo .= "<div style='background-color: #0056b3; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0;'>";
        $corpo .= "<h1>DocNet - Alerta Semanal</h1>";
        $corpo .= "</div>";
        
        $corpo .= "<div style='padding: 20px; border: 1px solid #ddd; border-top: none;'>";
        $corpo .= "<p>Olá Administrador,</p>";
        $corpo .= "<p>Identificamos os seguintes equipamentos/serviços com <strong>vencimento programado para os próximos 7 dias</strong>:</p>";
        
        $corpo .= "<table border='0' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse;'>";
        $corpo .= "<thead><tr style='background-color: #f2f2f2; text-align: left;'>";
        $corpo .= "<th style='border-bottom: 2px solid #ddd;'>Equipamento</th>";
        $corpo .= "<th style='border-bottom: 2px solid #ddd;'>Cliente</th>";
        $corpo .= "<th style='border-bottom: 2px solid #ddd;'>Vencimento</th></tr></thead>";
        $corpo .= "<tbody>";

        foreach ($this->listaVencimentos as $item) {
            $dataFormata = date('d/m/Y', strtotime($item['venc_contr']));
            $corpo .= "<tr>";
            $corpo .= "<td style='border-bottom: 1px solid #eee;'>{$item['name']}</td>";
            $corpo .= "<td style='border-bottom: 1px solid #eee;'>{$item['nome_fantasia']}</td>";
            $corpo .= "<td style='border-bottom: 1px solid #eee; color: #d9534f; font-weight: bold;'>{$dataFormata}</td>";
            $corpo .= "</tr>";
        }

        $corpo .= "</tbody></table>";
        
        $corpo .= "<p style='margin-top: 20px;'>Favor verificar as renovações pendentes no painel administrativo.</p>";
        $corpo .= "<a href='" . URLADM . "list-prod/index' style='display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Acessar Sistema DocNet</a>";
        $corpo .= "</div>";
        $corpo .= "<p style='font-size: 12px; color: #999; text-align: center;'>Este é um e-mail automático gerado pelo sistema DocNet.</p>";
        $corpo .= "</div>";

        // Prepara os dados para a Helper AdmsPhpMailer
        $emailData = [
            'toEmail' => 'docan2006@gmail.com', // E-mail de destino
            'toName' => 'Administrador DocNet',
            'subject' => '⚠️ Alerta: Vencimentos DocNet da Semana',
            'contentHtml' => $corpo,
            'contentText' => "Relatório de vencimentos semanal DocNet. Acesse o sistema para detalhes."
        ];

        // Dispara o envio
        $sendEmail = new \App\adms\Models\helper\AdmsPhpMailer();
        $sendEmail->sendEmail($emailData);
    }
}