<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller listar Tipos de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class ListTypeEquip
{
    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data;

    /** @var string|int|null $page Recebe o número página */
    private string|int|null $page;

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var string|null $searchTypeEquip Recebe o tipo do equipamento*/
    private string|null $searchTypeEquip;

    /**
     * Método listar Tipos de equipamentos
     * 
     * Instancia a MODELS responsável em buscar os registros no banco de dados.
     * Se encontrar registro no banco de dados envia para VIEW.
     * Senão enviar o array de dados vazio.
     *
     * @return void
     */
    public function index(string|int|null $page = null): void
    {
        $this->page = (int) $page ? $page : 1;

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        $this->searchTypeEquip = filter_input(INPUT_GET, 'searchTypeEquip', FILTER_DEFAULT);

        $listtypeequip= new \App\adms\Models\AdmsListTypeEquip();
        if (!empty($this->dataForm['SendSearchTypeEquip'])) {
            $this->page = 1;
            $listtypeequip->listSearchTypeEquip($this->page, $this->dataForm['search_type_equip']);
            $this->data['form'] = $this->dataForm;
        } elseif ((!empty($this->searchTypeEquip))) {
            $listtypeequip->listSearchTypeEquip($this->page, $this->searchTypeEquip);
            $this->data['form']['listtypeequip'] = $this->searchTypeEquip;
        } else {            
            $listtypeequip->listtypeequip($this->page);            
        }
        
        if ($listtypeequip->getResult()) {
            $this->data['listtypeequip'] = $listtypeequip->getResultBd();
            $this->data['pagination'] = $listtypeequip->getResultPg();
        } else {
            $this->data['listtypeequip'] = [];
            $this->data['pagination'] = "";
        }

        $button = ['add_type_equip' => ['menu_controller' => 'add-type-equip', 'menu_metodo' => 'index'],
        'view_type_equip' => ['menu_controller' => 'view-type-equip', 'menu_metodo' => 'index'],
        'edit_type_equip' => ['menu_controller' => 'edit-type-equip', 'menu_metodo' => 'index'],
        'delete_type_equip' => ['menu_controller' => 'delete-type-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-type-equip";         
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/listTypeEquip", $this->data);
        $loadView->loadView();
    }
}
