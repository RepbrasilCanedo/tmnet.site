<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class ListProd
{
    private array|string|null $data = [];
    private array|null $dataForm;
    private int $page;

    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ?: 1;
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Filtros via GET
        $sTipo  = filter_input(INPUT_GET, 'search_tipo', FILTER_DEFAULT);
        $sEmp   = filter_input(INPUT_GET, 'search_emp', FILTER_DEFAULT);
        $sProd  = filter_input(INPUT_GET, 'search_prod', FILTER_DEFAULT);
        $sSit   = filter_input(INPUT_GET, 'search_sit', FILTER_DEFAULT); 
        $dStart = filter_input(INPUT_GET, 'date_start', FILTER_DEFAULT);
        $dEnd   = filter_input(INPUT_GET, 'date_end', FILTER_DEFAULT);

        $listProd = new \App\adms\Models\AdmsListProd();

        if (isset($this->dataForm['SendExportProd'])) {
            $listProd->listSearchProd(1, $sTipo, $sEmp, $sProd, $dStart, $dEnd, $sSit);
            $this->exportToCsv($listProd->getResultBd());
        }

        if (!empty($this->dataForm['SendSearchProdEmp'])) {
            $this->page = 1;
            $listProd->listSearchProd($this->page, $this->dataForm['search_tipo'], $this->dataForm['search_emp'], $this->dataForm['search_prod'], $this->dataForm['date_start'], $this->dataForm['date_end'], $this->dataForm['search_sit']);
            $this->data['form'] = $this->dataForm;
        } elseif ($sTipo || $sEmp || $sProd || $dStart || $dEnd || $sSit) {
            $listProd->listSearchProd($this->page, $sTipo, $sEmp, $sProd, $dStart, $dEnd, $sSit);
            $this->data['form'] = ['search_tipo'=>$sTipo,'search_emp'=>$sEmp,'search_prod'=>$sProd,'date_start'=>$dStart,'date_end'=>$dEnd, 'search_sit'=>$sSit];
        } else {
            $listProd->listProd($this->page);
        }

        $this->data['listProd'] = $listProd->getResultBd() ?: [];
        $this->data['pagination'] = $listProd->getResultPg();
        $this->data['total_geral'] = $listProd->getTotalGeral();
        $this->data['total_vencidos'] = $listProd->getTotalVencidos();
        $this->data['total_avencer'] = $listProd->getTotalAVencer();
        $this->data['select'] = $listProd->listSelect();

        $button = ['add_prod'=>['menu_controller'=>'add-prod','menu_metodo'=>'index'],'view_prod'=>['menu_controller'=>'view-prod','menu_metodo'=>'index'],'edit_prod'=>['menu_controller'=>'edit-prod','menu_metodo'=>'index'],'delete_prod'=>['menu_controller'=>'delete-prod','menu_metodo'=>'index']];
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission($button);
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
        $this->data['sidebarActive'] = "list-prod";
        
        (new \Core\ConfigView("adms/Views/produtos/listProd", $this->data))->loadView();
    }

    private function exportToCsv($data) {
        if (!$data) return;
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=equipamentos_'.date('Ymd').'.csv');
        $out = fopen('php://output', 'w');
        
        fputcsv($out, ['ID', 'Nome', 'Tipo', 'Cliente', 'Contrato', 'Vencimento', 'Situacao'], ';');
        
        foreach ($data as $row) {
            $venc = (!empty($row['final_contr']) && $row['final_contr'] != '0000-00-00') ? date('d/m/Y', strtotime($row['final_contr'])) : 'Indeterminado';
            $contratoNome = $row['name_contr'] ?? 'Sem Contrato';
            
            fputcsv($out, [$row['id'], $row['name'], $row['name_type'], $row['nome_fantasia_clie'], $contratoNome, $venc, $row['name_sit']], ';');
        }
        
        fclose($out);
        exit;
    }
}