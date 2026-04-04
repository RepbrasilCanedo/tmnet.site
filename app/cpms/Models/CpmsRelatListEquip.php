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
class CpmsRelatListEquip
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
    public function searchEquipamentos(string $search_empresa, ?string $search_date_start, ?string $search_date_end): void
    { 
        // 2. Base da Query (O que é fixo em todas as buscas)
        // Usamos prod.empresa_id para garantir que o usuário só veja dados da empresa dele
        $query_base = " FROM adms_produtos AS prod
                        INNER JOIN adms_clientes AS clie ON clie.id=prod.cliente_id 
                        INNER JOIN adms_emp_principal AS emp ON emp.id=prod.empresa_id
                        WHERE (empresa_id= :empresa_id) ";

        $links = " empresa_id={$_SESSION['emp_user']}";


        // 3. Filtros Dinâmicos (Só entram na query se não estiverem vazios)

        if (!empty($search_empresa)) {
            $query_base .= " AND prod.cliente_id= :search_empresa";
            // Passe apenas o texto puro na URL
            $links .= "&search_empresa={$search_empresa}";
        }   

        if ((!empty($search_date_start)) and (!empty($search_date_end))){
            $query_base .= " AND prod.venc_contr BETWEEN :search_date_start AND :search_date_end";
            // Passe apenas o texto puro na URL
            $links .= "&search_date_start={$search_date_start}&search_date_end={$search_date_end}";
        }


        // 5. Consulta Principal (Busca dos dados)
        $listcham = new \App\adms\Models\helper\AdmsRead();
        $full_query =   "SELECT prod.id as id_prod, prod.name as name_prod, prod.serie as serie_prod, 
                        prod.modelo_id as modelo_id_prod, prod.marca_id as marca_id_prod, clie.nome_fantasia as nome_fantasia_clie, 
                        prod.empresa_id as empresa_id_prod, prod.venc_contr as venc_contr_prod, prod.sit_id, emp.logo as logo_emp " 
                    . $query_base . 
                    " ORDER BY prod.id DESC";

        $listcham->fullRead($full_query, $links);

        $this->resultBd = $listcham->getResult();

        

        // 6. Resposta para a View
        if ($this->resultBd) {
            //var_dump($this->resultBd);
            $this->generatePdf();
            // CAPTURA A CONTAGEM AQUI:
            $this->countRegistros = count($this->resultBd);
            
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

                $this->listRegistryAdd = ['nome_emp' => $registry['nome_emp']];
                return $this->listRegistryAdd;
            }
        } else {
            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_empresa as emp  
            ORDER BY nome_fantasia ASC");
            $registry['nome_emp'] = $list->getResult();

            $this->listRegistryAdd = ['nome_emp' => $registry['nome_emp']];
        }
        return $this->listRegistryAdd;
    }


    // Função para gerar os dados para o pdf em DOMPDF

    private function generatePdf()
    {      
        // Recupera o total que salvamos na propriedade da classe ou na sessão
        $total_tickets = $this->countRegistros ?? count($this->resultBd);
        $image_clie = $this->resultBd[0]['empresa_id_prod'];
        $logo_clie = $this->resultBd[0]['logo_emp'];

        // CSS ajustado para DOMPDF
        $html = "<style>
                    body { font-family: sans-serif; font-size: 11px; }
                    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
                    th, td { border: 1px solid #333; padding: 4px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .header-table { text-align: center; margin-bottom: 20px; }
                    .logo { float: right; }
                    .footer { font-size: 9px; margin-top: 20px; text-align: center; }
                </style>";
                

        // Topo com Logo e Título
        $html .= "<div>";
        $html .= "<img src='" . URLADM . "app/adms/assets/image/logo/clientes/$image_clie/$logo_clie' width='80'>";
        $html .= "</div>";
        
        $html .= "<div class='header-table'>";
        $html .= "<h2>RELATÓRIO DE EQUIPAMENTOS - DOCNET HELP DESK</h2>";
        $html .= "Total de Tickets: <strong>{$total_tickets}</strong>";
        $html .= "</div>";

        $html .= "<table>";
        $html .= "<thead>
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th>Nome do Equipamento</th>
                        <th>N. Série</th>
                        <th>Modelo</th>
                        <th>Marca</th>
                        <th>Vencimento Contrato</th>
                    </tr>
                </thead>";
        
        $html .= "<tbody>";

        foreach ($this->resultBd as $user) {
            extract($user);  
            
            $dt_cham_formatada = date('d/m/Y', strtotime($venc_contr_prod));
            

            $html .= "<tr>";
            $html .= "<td>$id_prod</td>";
            $html .= "<td>$nome_fantasia_clie</td>";
            $html .= "<td>$name_prod</td>";
            $html .= "<td>$serie_prod</td>";
            $html .= "<td>$modelo_id_prod</td>";
            $html .= "<td>$marca_id_prod</td>";
            $html .= "<td>$dt_cham_formatada</td>";
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
