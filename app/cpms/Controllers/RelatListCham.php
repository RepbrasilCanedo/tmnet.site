<?php

namespace App\cpms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar todas as chamadas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class RelatListCham
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchCham Recebe a empresa do chamado*/
    private string|null $searchId;

    /** @var string|null $searchCham Recebe a empresa do chamado*/
    private string|null $searchTipo;

    /** @var string|null $searchCham Recebe a empresa do chamado*/
    private string|null $searchEmpresa;

    /** @var string|null $searchCham Recebe o status do chamado*/
    private string|null $searchStatus;

    /** @var array|string|null $searchCham Recebe o status do chamado*/
    private string|null $statusCham;

    /** @var string|null $searchDateStart Recebe a data de inicio */
    private string|null $searchDateStart;

    /** @var string|null $searchDateEnd Recebe a data final */
    private string|null $searchDateEnd;

    /** @var string|null $searchDateEnd Recebe a data final */
    private string|null $searchTecSuporte;

    /** @var string|null $searchStatusChamado Recebe o status do chamado */
    private string|null $searchStatusChamado;

    /**
     * Método listar Chamados
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(): void
    {        
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);  
        
        if ((!empty($this->dataForm['SendSearchCham']))) {
            $listCham = new \App\cpms\Models\CpmsRelatListCham();
            $listCham->listSearchCham($this->dataForm['search_empresa'], $this->dataForm['search_status'], $this->dataForm['search_tipo'], $this->dataForm['search_date_start'], $this->dataForm['search_date_end'], $this->dataForm['search_tec_suporte']);
            $this->data['form'] = $this->dataForm;
        }

        $listSelect = new \App\cpms\Models\CpmsRelatListCham();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['sidebarActive'] = "adms/relat-list-cham";
        $loadView = new \Core\ConfigView("cpms/Views/chamados/relatListCham", $this->data);
        $loadView->loadView();
    }  
}
