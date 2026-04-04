<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar Tipo de equipamentos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditTypeEquip
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Tipo.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, 
     * se encontrar instancia o método "viewEditTypeEquip". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editTypeEquip".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditTypeEquip']))) {
            $this->id = (int) $id;
            $viewTypeEquip = new \App\adms\Models\AdmsEditTypeEquip();
            $viewTypeEquip->viewTypeEquip($this->id);
            if ($viewTypeEquip->getResult()) {
                $this->data['form'] = $viewTypeEquip->getResultBd();
                $this->viewEditTypeEquip();
            } else {
                $urlRedirect = URLADM . "list-type-equip/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editTypeEquip();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditTypeEquip(): void
    {
        $button = ['list_type_equip' => ['menu_controller' => 'list-type-equip', 'menu_metodo' => 'index'],
        'view_type_equip' => ['menu_controller' => 'view-type-equip', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-type-equip"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/editTypeEquip", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar Tipo.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a cor no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Tipo.
     *
     * @return void
     */
    private function editTypeEquip(): void
    {
        if (!empty($this->dataForm['SendEditTypeEquip'])) {
            unset($this->dataForm['SendEditTypeEquip']);
            $editTypeEquip = new \App\adms\Models\AdmsEditTypeEquip();
            $editTypeEquip->update($this->dataForm);
            if ($editTypeEquip->getResult()) {
                $urlRedirect = URLADM . "view-type-equip/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditTypeEquip();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo não encontrado!</p>";
            $urlRedirect = URLADM . "list-type-equip/index";
            header("Location: $urlRedirect");
        }
    }
}
