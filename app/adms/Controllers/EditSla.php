<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar sla
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditSla
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar sla.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da sla no banco de dados, se encontrar instancia o método "viewEditColor". Se não existir redireciona para o listar sla.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editSla".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditSla']))) {
            $this->id = (int) $id;
            $editSla = new \App\adms\Models\AdmsEditSla();
            $editSla->editSla($this->id);
            if ($editSla->getResult()) {
                $this->data['form'] = $editSla->getResultBd();
                $this->viewEditSla();
            } else {
                $urlRedirect = URLADM . "list-colors/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editSla();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditSla(): void
    {
        $button = ['list_sla' => ['menu_controller' => 'list-sla', 'menu_metodo' => 'index'],
        'view_sla' => ['menu_controller' => 'view-sla', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listSelect = new \App\adms\Models\AdmsEditSla();
        $this->data['select'] = $listSelect->listSelect();
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-sla"; 
        $loadView = new \Core\ConfigView("adms/Views/sla/editSla", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar sla.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a sla no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar sla.
     *
     * @return void
     */
    private function editSla(): void
    {
        if (!empty($this->dataForm['SendEditSla'])) {
            unset($this->dataForm['SendEditSla']);
            $editSla = new \App\adms\Models\AdmsEditSla();
            $editSla->update($this->dataForm);
            if ($editSla->getResult()) {
                $urlRedirect = URLADM . "edit-sla/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditSla();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: sla não encontrada!</p>";
            $urlRedirect = URLADM . "list-sla/index";
            header("Location: $urlRedirect");
        }
    }
}
