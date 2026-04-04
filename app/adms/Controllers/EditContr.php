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
class EditContr
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Contrato.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "viewEditContr". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editContr".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditContr']))) {
            $this->id = (int) $id;
            $viewContr = new \App\adms\Models\AdmsEditContr();
            $viewContr->viewContr($this->id);
            if ($viewContr->getResult()) {
                $this->data['form'] = $viewContr->getResultBd();
                $this->viewEditContr();
            } else {
                $urlRedirect = URLADM . "list-contr/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editContr();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditContr(): void
    {

        $button = ['list_contr' => ['menu_controller' => 'list-contr', 'menu_metodo' => 'index'],
        'view_contr' => ['menu_controller' => 'view-contr', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listSelect = new \App\adms\Models\AdmsEditContr();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contr";
        $loadView = new \Core\ConfigView("adms/Views/contratos/editContr", $this->data);
        $loadView->loadView();

    }

    /**
     * Editar Contrato.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o Contrato no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Contrato.
     *
     * @return void
     */
    private function editContr(): void
    {
        if (!empty($this->dataForm['SendEditContr'])) {
            unset($this->dataForm['SendEditContr']);
            $editContr = new \App\adms\Models\AdmsEditContr();
            $editContr->update($this->dataForm);
            
            if ($editContr->getResult()) {
                $urlRedirect = URLADM . "list-contr/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditContr();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Contrato não encontrado!</p>";
            $urlRedirect = URLADM . "list-contr/index";
            header("Location: $urlRedirect");
        }
    }
}
