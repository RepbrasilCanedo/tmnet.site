<?php

namespace App\cpms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar Slas dos Tikets do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class CpmsRelatListSla
{

    // Propriedade para armazenar o total
    private int $countRegistros; 

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;  

    /** @var array|null $listRegistryAdd Recebe os registros do banco de dados */
    private array|null $listRegistryAdd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os registros do BD
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }


    /**
     * Metodo faz a pesquisa dos chamados na tabela adms_cham e lista as informacoes na view
     * Recebe os paramentro "produto" para que seja feita a paginacao do resultado
     * Recebe o paramentro "search_prod" para que seja feita a pesquisa pelo produto
     * Recebe o paramentro "search_emp" para que seja feita a pesquisa pela empresa
     * @param integer|null $page
     * @param string|null $search_prod
     * @param string|null $search_emp
     * @return void
     */
    
public function listSearchSla(?string $search_ticket, ?string $search_empresa, ?string $search_suporte, ?string $search_tipo, ?string $search_date_start, ?string $search_date_end, ?string $search_status_anterior, ?string $search_status): void
{   

    // 2. Base da Query (O que é fixo em todas as buscas)
    // Usamos prod.empresa_id para garantir que o usuário só veja dados da empresa dele
    $query_base = " FROM adms_sla_hist AS sla_hist
                    INNER JOIN adms_clientes AS clie ON clie.id = sla_hist.cliente_id 
                    INNER JOIN adms_sla AS sla ON sla.id = sla_hist.id_sla 
                    INNER JOIN adms_cham_status AS sta_ant ON sta_ant.id = sla_hist.status_id_ant
                    INNER JOIN adms_cham_status AS sta_atu ON sta_atu.id = sla_hist.status_id
                    INNER JOIN adms_users AS user ON user.id = sla_hist.suporte_id
                    INNER JOIN adms_emp_principal AS emp ON emp.id=sla_hist.empresa_id 
                    WHERE (sla_hist.empresa_id= :empresa_id) ";

    $links = " empresa_id={$_SESSION['emp_user']}";

    // 3. Filtros Dinâmicos (Só entram na query se não estiverem vazios)
    if (!empty($search_ticket)) {
        $query_base .= " AND sla_hist.id_ticket = :id_ticket";
        $links .= "&id_ticket={$search_ticket}";
    }

    if (!empty($search_empresa)) {
        $query_base .= " AND sla_hist.cliente_id= :search_empresa";
        // Passe apenas o texto puro na URL
        $links .= "&search_empresa={$search_empresa}";
    }

    if (!empty($search_suporte)) {
        // Filtra pelo nome do suporte tecnico associado ao ticket
        $query_base .= " AND sla_hist.suporte_id= :search_suporte";
        $links .= "&search_suporte={$search_suporte}";
    }

    if (!empty($search_tipo)) {
        $query_base .= " AND sla_hist.id_sla = :search_tipo";
        $links .= "&search_tipo={$search_tipo}";
    }   

    if ((!empty($search_date_start)) and (!empty($search_date_end))){
        $query_base .= " AND sla_hist.dt_abert_ticket BETWEEN :search_date_start AND :search_date_end";
        // Passe apenas o texto puro na URL
        $links .= "&search_date_start={$search_date_start}&search_date_end={$search_date_end}";
    }

    if (!empty($search_status_anterior)) {
        // Filtra pelo status do ticket
        $query_base .= " AND sla_hist.status_id_ant = :search_status_anterior";
        $links .= "&search_status_anterior={$search_status_anterior}";
    }

    if (!empty($search_status)) {
        // Filtra pelo status do ticket
        $query_base .= " AND sla_hist.status_id = :search_status";
        $links .= "&search_status={$search_status}";
    }
    

    // 5. Consulta Principal (Busca dos dados)
    $listslas = new \App\adms\Models\helper\AdmsRead();
    $full_query = "SELECT sla_hist.id AS id_sla_hist, sla_hist.empresa_id, clie.nome_fantasia AS nome_fantasia_clie, sla_hist.id_ticket AS id_ticket_sla_hist, sla_hist.dt_abert_ticket as dt_abert_ticket, 
                            sla.name as name_sla, sla_hist.tempo_sla_prim AS tempo_sla_prim, sla_hist.tempo_sla_fin AS tempo_sla_fin, sta_ant.name AS name_status_id_ant, 
                            sla_hist.dt_status_ant AS dt_status_ant, sta_atu.name AS name_sta_atu, sla_hist.dt_status AS dt_status, user.name  AS name_user, sla_hist.tempo_sla AS tempo_sla, emp.logo as logo_emp " 
                            . $query_base . 
                            " ORDER BY sla_hist.dt_status";

    $listslas->fullRead($full_query, $links);

    $this->resultBd = $listslas->getResult();

    // CAPTURA A CONTAGEM AQUI:
    $this->countRegistros = count($this->resultBd);

    // 6. Resposta para a View
    if ($this->resultBd) {
        $this->generatePdf();
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum sla dos tickets encontrado com o(s) filtro(s) aplicado(s)!</div>";
        $this->result = false;
    }
}






    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect()
    {
        if ($_SESSION['adms_access_level_id'] > 2) {

            if ($_SESSION['adms_access_level_id'] == 4) {

                    $listTipo = new \App\adms\Models\helper\AdmsRead();                
                    $listTipo->fullRead("SELECT id, name FROM adms_sla
                    WHERE empresa_id= :empresa", "empresa={$_SESSION['emp_user']}");
                    $registry['nome_tipo'] = $listTipo->getResult();

                    $listClie = new \App\adms\Models\helper\AdmsRead();                
                    $listClie->fullRead("SELECT id, nome_fantasia FROM adms_clientes
                    WHERE empresa= :empresa", "empresa={$_SESSION['emp_user']}");
                    $registry['nome_clie'] = $listClie->getResult();

                    $listSup = new \App\adms\Models\helper\AdmsRead();                
                    $listSup->fullRead("SELECT id, name FROM adms_users
                    WHERE empresa_id= :empresa AND adms_access_level_id= :nivel_acesso ORDER BY name", "empresa={$_SESSION['emp_user']}&nivel_acesso=12");
                    $registry['nome_sup'] = $listSup->getResult();

                    $listSta = new \App\adms\Models\helper\AdmsRead();
                    $listSta->fullRead("SELECT id, name FROM adms_cham_status  ORDER BY name ASC");
                    $registry['nome_status'] = $listSta->getResult();

                    $this->listRegistryAdd = ['nome_tipo' => $registry['nome_tipo'], 'nome_clie' => $registry['nome_clie'], 'nome_sup' => $registry['nome_sup'], 'nome_status' => $registry['nome_status']];
                    return $this->listRegistryAdd;
            }
        } else{
                    $listTipo = new \App\adms\Models\helper\AdmsRead();                
                    $listTipo->fullRead("SELECT id, name FROM adms_sla");
                    $registry['nome_tipo'] = $listTipo->getResult();

                    $listClie = new \App\adms\Models\helper\AdmsRead();                
                    $listClie->fullRead("SELECT id, nome_fantasia FROM adms_clientes}");
                    $registry['nome_clie'] = $listClie->getResult();

                    $listSup = new \App\adms\Models\helper\AdmsRead();                
                    $listSup->fullRead("SELECT id, name FROM adms_users
                    WHERE  adms_access_level_id= :nivel_acesso ORDER BY name", "nivel_acesso=12");
                    $registry['nome_sup'] = $listSup->getResult();

                    $listSta = new \App\adms\Models\helper\AdmsRead();
                    $listSta->fullRead("SELECT id, name FROM adms_cham_status  ORDER BY name ASC");
                    $registry['nome_status'] = $listSta->getResult();

                    $this->listRegistryAdd = ['nome_tipo' => $registry['nome_tipo'], 'nome_clie' => $registry['nome_clie'], 'nome_sup' => $registry['nome_sup'], 'nome_status' => $registry['nome_status']];
                    return $this->listRegistryAdd;
        }
    }


    // Função para gerar os dados para o pdf em DOMPDF

    private function generatePdf()
    {      
        // Recupera o total que salvamos na propriedade da classe ou na sessão
        $total_tickets = $this->countRegistros ?? count($this->resultBd);
        $image_clie = $this->resultBd[0]['empresa_id'];
        $logo_emp = $this->resultBd[0]['logo_emp'];

        // CSS ajustado para DOMPDF
        $html = "<style>
                    body { font-family: sans-serif; font-size: 11px; }
                    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
                    th, td { border: 1px solid #333; padding: 4px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .header-table { text-align: center; margin-bottom: 20px; }
                    .logo { float: right; }
                    .footer { font-size: 9px; margin-top: 20px; text-align: center; }
                    .status-aberto { color: #d9534f; font-weight: bold; } /* Vermelho */
                    .status-finalizado { color: #5cb85c; font-weight: bold; } /* Verde */
                </style>";
                

        // Topo com Logo e Título
        $html .= "<div>";
        $html .= "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/$image_clie/$logo_emp' width='80'>";
        $html .= "</div>";
        
        $html .= "<div class='header-table'>";
        $html .= "<h2>RELATÓRIO DOS SLAS DOS TICKETS - DOCNET HELP DESK</h2>";
        $html .= "Total de Tickets: <strong>{$total_tickets}</strong>";
        $html .= "</div>";

        $html .= "<table>";
        $html .= "<thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Cliente</th>
                        <th>Suporte</th>
                        <th>Sla</th>
                        <th>Tempo</th>
                        <th>Abertura Ticket</th>
                        <th>Status Anterior</th>
                        <th>Status</th>
                        <th>Tempo Sla</th>
                    </tr>
                </thead>";
        
        $html .= "<tbody>";

        foreach ($this->resultBd as $user) {
            extract($user);  
            
            $dt_cham_formatada = date('d/m/Y', strtotime($dt_abert_ticket));
            $dt_status_ant = date('d/m/Y H:i:s ', strtotime($dt_status_ant));
            $dt_status = date('d/m/Y H:i:s', strtotime($dt_status));
            
            // Lógica de cores para o Status (ajuste os nomes conforme seu banco)
           /* $styleStatus = "";
            if ($name_sta_atu == "Aberto") { $styleStatus = "class='status-aberto'"; }
            if ($name_sta_atu == "Finalizado") { $styleStatus = "class='status-finalizado'"; }*/

            $html .= "<tr>";
            $html .= "<td>$id_ticket_sla_hist</td>";
            $html .= "<td>$nome_fantasia_clie</td>";
            $html .= "<td>$name_user</td>";
            $html .= "<td>$name_sla</td>";
            $html .= "<td>$tempo_sla_prim</td>";
            $html .= "<td>$dt_cham_formatada</td>";
            $html .= "<td>$name_status_id_ant - $dt_status_ant</td>";
            $html .= "<td>$name_sta_atu - $dt_status</td>";
            $html .= "<td>$tempo_sla</td>";            
            $html .= "</tr>";
        }
        
        $html .= "</tbody>";
        $html .= "</table>";
        
        date_default_timezone_set('America/Bahia');
        $dataGeracao = date('d/m/Y H:i:s');
        $html .= "<div class='footer'>Relatório gerado em : $dataGeracao</div>";

        $generatePdf = new \App\cpms\Models\helper\CpmsGeneratePdf();
        $generatePdf->generatePdf($html);
    }    
}
