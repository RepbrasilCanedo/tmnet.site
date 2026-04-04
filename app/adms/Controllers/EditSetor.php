<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar Setores da empresa
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditSetor
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar Setor.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da empresa no banco de dados, 
     * se encontrar instancia o método "viewEditSetor". Se não existir redireciona para o listar Setor.
     * 
     * Se não existir a empresa clicar no botão acessa o ELSE e instancia o método "editSetor".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditSetor']))) {
            $this->id = (int) $id;
            $viewSetor = new \App\adms\Models\AdmsEditSetor();
            $viewSetor->viewSetor($this->id);
            if ($viewSetor->getResult()) {
                $this->data['form'] = $viewSetor->getResultBd();
                $this->viewEditSetor();
            } else {
                $urlRedirect = URLADM . "list-setor/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editSetor();
        }
    }

    /**
     * Instanciar a MODELS e o método "listSelect" responsável em buscar os dados para preencher o campo SELECT 
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditSetor(): void
    {
        $button = ['list_setor' => ['menu_controller' => 'list-setor', 'menu_metodo' => 'index'],
        'view_setor' => ['menu_controller' => 'view-setor', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsAddSetor();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 

        $this->data['sidebarActive'] = "list-setor";
        $loadView = new \Core\ConfigView("adms/Views/empresas/editSetor", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar página.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente a página no banco de dados.
     * Se o usuário não clicou no botão redireciona para página listar página.
     *
     * @return void
     */
    private function editSetor(): void
    {
        if (!empty($this->dataForm['SendEditSetor'])) {
            unset($this->dataForm['SendEditSetor']);
            $editSetor = new \App\adms\Models\AdmsEditSetor();
            $editSetor->update($this->dataForm);
            if ($editSetor->getResult()) {
                $urlRedirect = URLADM . "list-setor/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditSetor();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Setor da empresa não encontrada!</p>";
            $urlRedirect = URLADM . "list-setor/index";
            header("Location: $urlRedirect");
        }
    }
}
