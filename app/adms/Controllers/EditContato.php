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
class EditContato
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
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da cor no banco de dados, se encontrar instancia o método "vieweditContato". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editContato".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditContato']))) {
            $this->id = (int) $id;
            $viewContato = new \App\adms\Models\AdmsEditContato();
            $viewContato->viewContato($this->id);

            if ($viewContato->getResult()) {
                $this->data['form'] = $viewContato->getResultBd();
                $this->viewEditContato();
            } else {
                $urlRedirect = URLADM . "list-contato/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editContato();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditContato(): void
    {
        $button = ['list_contato' => ['menu_controller' => 'list-contato', 'menu_metodo' => 'index'],
        'view_contato' => ['menu_controller' => 'view-contato', 'menu_metodo' => 'index']];

        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contato"; 
        $loadView = new \Core\ConfigView("adms/Views/contato/editContato", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar mensagem.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a cor no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar mensagem.
     *
     * @return void
     */
    private function editContato(): void
    {
        if (!empty($this->dataForm['SendEditContato'])) {
            unset($this->dataForm['SendEditContato']);

            $editContato = new \App\adms\Models\AdmsEditcontato();
            $editContato->update($this->dataForm);

            if ($editContato->getResult()) {
                $urlRedirect = URLADM . "list-contato/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditContato();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Mensagem não encontrada!</p>";
            $urlRedirect = URLADM . "list-contato/index";
            header("Location: $urlRedirect");
        }
    }
}
