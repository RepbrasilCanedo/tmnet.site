<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar Modelo de ewquipamento
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditModelo
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Modelo.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "viewEditModelo". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editModelo".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditModelo']))) {
            $this->id = (int) $id;
            $viewModelo = new \App\adms\Models\AdmsEditModelo();
            $viewModelo->viewModelo($this->id);
            if ($viewModelo->getResult()) {
                $this->data['form'] = $viewModelo->getResultBd();
                $this->viewEditModelo();
            } else {
                $urlRedirect = URLADM . "list-modelo/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editModelo();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditModelo(): void
    {
        $button = ['list_modelo' => ['menu_controller' => 'list-modelo', 'menu_metodo' => 'index'],
        'view_modelo' => ['menu_controller' => 'view-modelo', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-modelo"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/editModelo", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar Modelo.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a cor no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Modelo.
     *
     * @return void
     */
    private function editModelo(): void
    {
        if (!empty($this->dataForm['SendEditModelo'])) {
            unset($this->dataForm['SendEditModelo']);
            $editModelo = new \App\adms\Models\AdmsEditModelo();
            $editModelo->update($this->dataForm);
            if ($editModelo->getResult()) {
                $urlRedirect = URLADM . "view-modelo/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditModelo();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Modelo não encontrado!</p>";
            $urlRedirect = URLADM . "list-modelo/index";
            header("Location: $urlRedirect");
        }
    }
}
