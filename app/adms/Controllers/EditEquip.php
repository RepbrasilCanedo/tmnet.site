<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar cor
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditEquip
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Equipamentos.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "viewEditEquip". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editEquip".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditEquip']))) {
            $this->id = (int) $id;
            $viewEquip = new \App\adms\Models\AdmsEditEquip();
            $viewEquip->viewEquip($this->id);
            if ($viewEquip->getResult()) {
                $this->data['form'] = $viewEquip->getResultBd();
                $this->viewEditEquip();
            } else {
                $urlRedirect = URLADM . "list-equip/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editEquip();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditEquip(): void
    {

        $button = ['list_equip' => ['menu_controller' => 'list-equip', 'menu_metodo' => 'index'],
        'view_equip' => ['menu_controller' => 'view-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listSelect = new \App\adms\Models\AdmsEditEquip();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-equip";
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/editEquip", $this->data);
        $loadView->loadView();

    }

    /**
     * Editar Equipamento.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o equipamento no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Equipamentos.
     *
     * @return void
     */
    private function editEquip(): void
    {
        if (!empty($this->dataForm['SendEditEquip'])) {
            unset($this->dataForm['SendEditEquip']);
            $editEquip = new \App\adms\Models\AdmsEditEquip();
            $editEquip->update($this->dataForm);
            if ($editEquip->getResult()) {
                $urlRedirect = URLADM . "view-equip/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditEquip();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Equipamento não encontrado!</p>";
            $urlRedirect = URLADM . "list-equip/index";
            header("Location: $urlRedirect");
        }
    }
}
