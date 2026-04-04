<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar produtos
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditProd
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Produtos.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "viewEditProd". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editProd".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditProd']))) {
            
            $this->id = (int) $id;
            $viewProd = new \App\adms\Models\AdmsEditProd();
            $viewProd->viewProd($this->id);

            if ($viewProd->getResult()) {
                $this->data['form'] = $viewProd->getResultBd();
                $this->viewEditProd();
            } else {
                $urlRedirect = URLADM . "list-prod/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editProd();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditProd(): void
    {

        $button = ['list_prod' => ['menu_controller' => 'list-prod', 'menu_metodo' => 'index'],
        'view_prod' => ['menu_controller' => 'view-prod', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listSelect = new \App\adms\Models\AdmsEditProd();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-prod";
        $loadView = new \Core\ConfigView("adms/Views/produtos/editProd", $this->data);
        $loadView->loadView();

    }

    /**
     * Editar Prodamento.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o prodamento no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Produtos.
     *
     * @return void
     */
    private function editProd(): void
    {
        if (!empty($this->dataForm['SendEditProd'])) {
            unset($this->dataForm['SendEditProd']);
            $editProd = new \App\adms\Models\AdmsEditProd();
            $editProd->update($this->dataForm);
            if ($editProd->getResult()) {
                $urlRedirect = URLADM . "view-prod/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProd();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Produto não encontrado!</p>";
            $urlRedirect = URLADM . "list-prod/index";
            header("Location: $urlRedirect");
        }
    }
}
