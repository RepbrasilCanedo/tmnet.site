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
class EditTipEqui
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar tipo de equipamento.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações do tipo de equipamento no banco de dados, se encontrar instancia o método "vieweditTipEqui". Se não existir redireciona para o listar cor.
     * 
     * Se não existir o usuário clicar no botão acessa o ELSE e instancia o método "editTipEqui".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($id)) and (empty($this->dataForm['SendEditTipEqui']))) {
            $this->id = (int) $id;
            $viewTipEqui = new \App\adms\Models\AdmsEditTipEqui();
            $viewTipEqui->viewTipEqui($this->id);
            if ($viewTipEqui->getResult()) {
                $this->data['form'] = $viewTipEqui->getResultBd();
                $this->vieweditTipEqui();
            } else {
                $urlRedirect = URLADM . "list-tip-equi/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editTipEqui();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditTipEqui(): void
    {
        $button = ['list_tip_equi' => ['menu_controller' => 'list-tip-equi', 'menu_metodo' => 'index'],
        'view_tip_equi' => ['menu_controller' => 'view-tip-equi', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsEditTipEqui();
        $this->data['select'] = $listSelect->listSelect();
        
        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-tip-equi"; 
        $loadView = new \Core\ConfigView("adms/Views/produtos/editTipEqui", $this->data);
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
    private function editTipEqui(): void
    {
        if (!empty($this->dataForm['SendEditTipEqui'])) {
            unset($this->dataForm['SendEditTipEqui']);
            $editTipEqui = new \App\adms\Models\AdmsEditTipEqui();
            $editTipEqui->update($this->dataForm);
            if ($editTipEqui->getResult()) {
                $urlRedirect = URLADM . "list-tip-equi/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->vieweditTipEqui();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Tipo de equipamento não encontrado!</p>";
            $urlRedirect = URLADM . "list-tip-equi/index";
            header("Location: $urlRedirect");
        }
    }
}
