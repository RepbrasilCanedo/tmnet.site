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
class EditMarca
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Marca.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "viewEditMarca". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editMarca".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditMarca']))) {
            $this->id = (int) $id;
            $viewMarca = new \App\adms\Models\AdmsEditMarca();
            $viewMarca->viewMarca($this->id);
            if ($viewMarca->getResult()) {
                $this->data['form'] = $viewMarca->getResultBd();
                $this->viewEditMarca();
            } else {
                $urlRedirect = URLADM . "list-marca/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editMarca();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditMarca(): void
    {
        $button = ['list_marca' => ['menu_controller' => 'list-marca', 'menu_metodo' => 'index'],
        'view_marca' => ['menu_controller' => 'view-marca', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-marca"; 
        $loadView = new \Core\ConfigView("adms/Views/equipamentos/editMarca", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar Marca.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a cor no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar Marca.
     *
     * @return void
     */
    private function editMarca(): void
    {
        if (!empty($this->dataForm['SendEditMarca'])) {
            unset($this->dataForm['SendEditMarca']);
            $editMarca = new \App\adms\Models\AdmsEditMarca();
            $editMarca->update($this->dataForm);
            if ($editMarca->getResult()) {
                $urlRedirect = URLADM . "view-marca/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditMarca();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Marca não encontrada!</p>";
            $urlRedirect = URLADM . "list-marca/index";
            header("Location: $urlRedirect");
        }
    }
}
