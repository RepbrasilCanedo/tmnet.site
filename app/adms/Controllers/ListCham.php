<?php
namespace App\adms\Controllers;

class ListCham {
    private array $data = [];

    public function index(string|int|null $page = null): void {
        $pageInt = (int)$page > 0 ? (int)$page : 1;
        $dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($_GET['clear'])) {
            unset($_SESSION['search_filter']);
            header("Location: " . URLADM . "list-cham/index");
            exit;
        }

        if (!empty($dataForm['SendSearchCham'])) {
            // VALIDAÇÃO DE PERÍODO: Início não pode ser maior que Fim
            if (!empty($dataForm['search_date_start']) && !empty($dataForm['search_date_end'])) {
                if ($dataForm['search_date_start'] > $dataForm['search_date_end']) {
                    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: A data de início não pode ser maior que a data final!</div>";
                    header("Location: " . URLADM . "list-cham/index");
                    exit;
                }
            }

            if ((int)$_SESSION['adms_access_level_id'] == 14) {
                $dataForm['search_empresa'] = (int)$_SESSION['set_clie'];
            }
            $_SESSION['search_filter'] = $dataForm;
            $pageInt = 1;
            session_write_close(); 
        } elseif (isset($_GET['status_ticket'])) {
            $_SESSION['search_filter'] = ['search_status' => (int)$_GET['status_ticket']];
            $pageInt = 1;
        }

        $filter = $_SESSION['search_filter'] ?? null;
        $model = new \App\adms\Models\AdmsListCham();
        $model->listCham($pageInt, $filter);

        $this->data['listCham'] = $model->getResultBd();
        $this->data['pagination'] = $model->getResultPg();
        $this->data['form'] = $filter;
        $this->data['select'] = $model->listSelect();
        
        $this->data['menu'] = (new \App\adms\Models\helper\AdmsMenu())->itemMenu();
        $this->data['button'] = (new \App\adms\Models\helper\AdmsButton())->buttonPermission([
            'view_cham' => ['menu_controller' => 'view-cham', 'menu_metodo' => 'index'],
            'edit_cham' => ['menu_controller' => 'edit-cham', 'menu_metodo' => 'index']
        ]);
        $this->data['sidebarActive'] = "list-cham";
        (new \Core\ConfigView("adms/Views/chamados/listCham", $this->data))->loadView();
    }
}