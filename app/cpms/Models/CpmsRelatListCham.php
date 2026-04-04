<?php

namespace App\cpms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Listar marcas dos equipamentos do banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class CpmsRelatListCham
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
public function listSearchCham(string $search_empresa, ?string $search_status, ?string $search_tipo, ?string $search_date_start, ?string $search_date_end, ?string $search_suporte): void
{ 
    // 2. Base da Query (O que é fixo em todas as buscas)
    // Usamos prod.empresa_id para garantir que o usuário só veja dados da empresa dele
    $query_base = " FROM adms_cham AS cham
                    INNER JOIN adms_clientes AS clie ON clie.id=cham.cliente_id                             
                    INNER JOIN adms_sla AS sla ON sla.id=cham.sla_id   
                    INNER JOIN adms_cham_status AS sta ON sta.id=cham.status_id   
                    INNER JOIN adms_users AS user ON user.id=cham.suporte_id                              
                    INNER JOIN adms_produtos AS prod ON prod.id=cham.prod_id                             
                    INNER JOIN adms_emp_principal AS emp ON emp.id=cham.empresa_id
                    WHERE cham.empresa_id= :empresa_id ";

    $links = " empresa_id={$_SESSION['emp_user']}";


    // 3. Filtros Dinâmicos (Só entram na query se não estiverem vazios)

    if (!empty($search_empresa)) {
        $query_base .= " AND cham.cliente_id= :search_empresa";
        // Passe apenas o texto puro na URL
        $links .= "&search_empresa={$search_empresa}";
    }
    if (!empty($search_status)) {
        // Filtra pelo status do ticket
        $query_base .= " AND cham.status_id = :search_status";
        $links .= "&search_status={$search_status}";
    }

    if (!empty($search_tipo)) {
        $query_base .= " AND cham.type_cham = :search_tipo";
        $links .= "&search_tipo={$search_tipo}";
    }   

    if ((!empty($search_date_start)) and (!empty($search_date_end))){
        $query_base .= " AND cham.dt_cham BETWEEN :search_date_start AND :search_date_end";
        // Passe apenas o texto puro na URL
        $links .= "&search_date_start={$search_date_start}&search_date_end={$search_date_end}";
    }

    if (!empty($search_suporte)) {
        // Filtra pelo nome do suporte tecnico associado ao cliente
        $query_base .= " AND cham.suporte_id= :search_suporte";
        $links .= "&search_suporte={$search_suporte}";
    }


    // 5. Consulta Principal (Busca dos dados)
    $listcham = new \App\adms\Models\helper\AdmsRead();
    $full_query = "SELECT cham.id as id_cham, cham.empresa_id as empresa_id_cham, cham.status_id, cham.suporte_id, user.name as name_user, clie.nome_fantasia as nome_fantasia_clie, sla.name as name_sla, 
                   prod.name as name_prod, cham.contato as contato_cham, cham.tel_contato as tel_contato_cham, emp.logo as logo_clie,
                   cham.dt_cham as dt_cham_cham, sta.name AS name_sta, cham.dt_status, cham.type_cham as tipo_cham " 
                  . $query_base . 
                  " ORDER BY cham.id DESC";

    $listcham->fullRead($full_query, $links);

    $this->resultBd = $listcham->getResult();

    // CAPTURA A CONTAGEM AQUI:
    $this->countRegistros = count($this->resultBd);

    // 6. Resposta para a View
    if ($this->resultBd) {
        $this->generatePdf();
        
    } else {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhum ticket encontrado com o(s) filtro(s) aplicado(s)!</div>";
        $this->result = false;
    }
    
}
  


    /**
     * Metodo para pesquisar as informações que serão usadas no dropdown do formulário
     *
     * @return array
     */
    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();

        if ($_SESSION['adms_access_level_id'] > 2) {

            if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] == 12)) {

                $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes as emp
                WHERE empresa= :empresa ORDER BY nome_fantasia", "empresa={$_SESSION['emp_user']}");
                $registry['nome_emp'] = $list->getResult();

                $list = new \App\adms\Models\helper\AdmsRead();
                $list->fullRead("SELECT id, name FROM adms_cham_status  ORDER BY name ASC");
                $registry['nome_status'] = $list->getResult();

                $list = new \App\adms\Models\helper\AdmsRead();
                $list->fullRead("SELECT id, name FROM adms_users
                WHERE empresa_id= :empresa and adms_access_level_id > {$_SESSION['adms_access_level_id']} ORDER BY name", "empresa={$_SESSION['emp_user']}");
                $registry['nome_emp_user'] = $list->getResult();

                $this->listRegistryAdd = ['nome_emp' => $registry['nome_emp'], 'nome_status' => $registry['nome_status'], 'nome_emp_user' => $registry['nome_emp_user']];
                return $this->listRegistryAdd;
            }
        } else {
            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa as emp  
            ORDER BY nome_fantasia ASC");
            $registry['nome_emp'] = $list->getResult();

            $list->fullRead("SELECT id, name FROM adms_cham_status  ORDER BY name ASC");
            $registry['nome_status'] = $list->getResult();

            $list->fullRead("SELECT id, name FROM adms_cham_type  ORDER BY name ASC");
            $registry['nome_type'] = $list->getResult();

            $this->listRegistryAdd = ['nome_emp' => $registry['nome_emp'], 'nome_status' => $registry['nome_status'], 'nome_type' => $registry['nome_type']];
        }
        return $this->listRegistryAdd;
    }


    // Função para gerar os dados para o pdf em DOMPDF

    private function generatePdf()
    {      
        // Recupera o total que salvamos na propriedade da classe ou na sessão
        $total_tickets = $this->countRegistros ?? count($this->resultBd);
        $image_clie = $this->resultBd[0]['empresa_id_cham'];
        $logo_clie = $this->resultBd[0]['logo_clie'];

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
        $html .= "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/$image_clie/$logo_clie' width='80'>";
        $html .= "</div>";
        
        $html .= "<div class='header-table'>";
        $html .= "<h2>RELATÓRIO DE TICKETS - DOCNET HELP DESK</h2>";
        $html .= "Total de Tickets: <strong>{$total_tickets}</strong>";
        $html .= "</div>";

        $html .= "<table>";
        $html .= "<thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Cliente</th>
                        <th>Contato</th>
                        <th>Telefone</th>
                        <th>Suporte</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th>Tipo</th>
                    </tr>
                </thead>";
        
        $html .= "<tbody>";

        foreach ($this->resultBd as $user) {
            extract($user);  
            
            $dt_cham_formatada = date('d/m/Y', strtotime($dt_cham_cham));
            
            // Lógica de cores para o Status (ajuste os nomes conforme seu banco)
            $styleStatus = "";
            if ($name_sta == "Aberto") { $styleStatus = "class='status-aberto'"; }
            if ($name_sta == "Finalizado") { $styleStatus = "class='status-finalizado'"; }

            $html .= "<tr>";
            $html .= "<td>$id_cham</td>";
            $html .= "<td>$nome_fantasia_clie</td>";
            $html .= "<td>$contato_cham</td>";
            $html .= "<td>$tel_contato_cham</td>";
            $html .= "<td>$name_user</td>";
            $html .= "<td>$dt_cham_formatada</td>";
            $html .= "<td $styleStatus>$name_sta</td>";
            $html .= "<td>$tipo_cham</td>";
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
