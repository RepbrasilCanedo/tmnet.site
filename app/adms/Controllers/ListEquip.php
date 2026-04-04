<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListEquip
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $dataForm;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var string|null $searchEquip Recebe o nome do controller */
    private string|null $searchEquip;

    /** @var string|null $searchEmail Recebe o nome do metodo */
    private string|null $searchEmp;

    /**
     * Método listar páginas.
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null): void
    {


        if (($_SESSION['adms_access_level_id'] > 1) and ($_SESSION['adms_access_level_id'] <> 7) and ($_SESSION['adms_access_level_id'] <> 2)) {

            $this->page = (int) $page ? $page : 1;

            $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $this->searchEquip = filter_input(INPUT_GET, 'search_equip', FILTER_DEFAULT);
            $this->searchEmp = filter_input(INPUT_GET, 'search_emp', FILTER_DEFAULT);

            $listEquip = new \App\adms\Models\AdmsListEquip();
            if (!empty($this->dataForm['SendSearchEquipEmp'])) {
                $this->page = 1;
                $listEquip->listSearchEquip($this->page, $this->dataForm['search_equip'], $this->dataForm['search_emp']);
                $this->data['form'] = $this->dataForm;
            } elseif ((!empty($this->searchEquip)) or (!empty($this->searchEmp))) {
                $listEquip->listSearchEquip($this->page, $this->searchEquip, $this->searchEmp);
                $this->data['form']['search_equip'] = $this->searchEquip;
                $this->data['form']['search_emp'] = $this->searchEmp;
            } else {
                $listEquip->listEquip($this->page);
            }

            if ($listEquip->getResult()) {
                $this->data['listEquip'] = $listEquip->getResultBd();
                $this->data['pagination'] = $listEquip->getResultPg();
            } else {
                $this->data['listEquip'] = [];
                $this->data['pagination'] = "";
            }
        } else {
            $this->page = (int) $page ? $page : 1;

            $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $this->searchEquip = filter_input(INPUT_GET, 'search_equip', FILTER_DEFAULT);
            $this->searchEmp = filter_input(INPUT_GET, 'search_emp', FILTER_DEFAULT);

            $listEquip = new \App\adms\Models\AdmsListEquip();
            if (!empty($this->dataForm['SendSearchEquipEmp'])) {
                $this->page = 1;
                $listEquip->listSearchEquip($this->page, $this->dataForm['search_equip'], $this->dataForm['search_emp']);
                $this->data['form'] = $this->dataForm;
            } elseif ((!empty($this->searchEquip)) or (!empty($this->searchEmp))) {
                $listEquip->listSearchEquip($this->page, $this->searchEquip, $this->searchEmp);
                $this->data['form']['search_equip'] = $this->searchEquip;
                $this->data['form']['search_emp'] = $this->searchEmp;
            } else {
                $listEquip->listEquip($this->page);
            }

            if ($listEquip->getResult()) {
                $this->data['listEquip'] = $listEquip->getResultBd();
                $this->data['pagination'] = $listEquip->getResultPg();
            } else {
                $this->data['listEquip'] = [];
                $this->data['pagination'] = "";
            }
        }


        $button = [
            'add_equip' => ['menu_controller' => 'add-equip', 'menu_metodo' => 'index'],
            'view_equip' => ['menu_controller' => 'view-equip', 'menu_metodo' => 'index'],
            'edit_equip' => ['menu_controller' => 'edit-equip', 'menu_metodo' => 'index'],
            'delete_equip' => ['menu_controller' => 'delete-equip', 'menu_metodo' => 'index']
        ];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['pag'] = $this->page;
        $this->data['sidebarActive'] = "list-equip";
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/listEquip", $this->data);
        $loadView->loadView();
    }
}
