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
class editAviso
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar cor.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "vieweditAviso". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editAviso".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditAviso']))) {
            $this->id = (int) $id;
            $viewAviso = new \App\adms\Models\AdmsEditAviso();
            $viewAviso->viewAviso($this->id);
            if ($viewAviso->getResult()) {
                $this->data['form'] = $viewAviso->getResultBd();
                $this->viewEditAviso();
            } else {
                $urlRedirect = URLADM . "list-aviso/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editAviso();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditAviso(): void
    {
        $button = ['list_aviso' => ['menu_controller' => 'list-aviso', 'menu_metodo' => 'index']];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-aviso"; 
        $loadView = new \Core\ConfigView("adms/Views/avisos/editAviso", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar cor.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a cor no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar cor.
     *
     * @return void
     */
    private function editAviso(): void
    {
        if (!empty($this->dataForm['SendEditAviso'])) {
            unset($this->dataForm['SendEditAviso']);
            $editAviso = new \App\adms\Models\AdmsEditAviso();
            $editAviso->update($this->dataForm);
            if ($editAviso->getResult()) {
                $urlRedirect = URLADM . "list-aviso/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditAviso();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Aviso não encontrado!</p>";
            $urlRedirect = URLADM . "list-aviso/index";
            header("Location: $urlRedirect");
        }
    }
}
