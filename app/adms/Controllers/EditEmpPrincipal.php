<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar empresas
 * @author Daniel Canedo - docan2006@gmail.com
 */
class EditEmpPrincipal
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;

    /**
     * Método editar empresas.
     * Receber os dados do formulário.
     * 
     * Se o parâmetro ID e diferente de vazio e o usuário não clicou no botão editar, instancia a MODELS para recuperar as informações da empresa no banco de dados, 
     * se encontrar instancia o método "viewEditEmpresas". Se não existir redireciona para o listar empresas.
     * 
     * Se não existir a empresa clicar no botão acessa o ELSE e instancia o método "editEmpresas".
     * 
     * @return void
     */
    public function index(int|string|null $id = null): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if ((!empty($id)) and (empty($this->dataForm['SendEditEmpPrincipal']))) {
            $this->id = (int) $id;
            $editEmpPrincipal = new \App\adms\Models\AdmsEditEmpPrincipal();
            $editEmpPrincipal->viewEmpPrincipal($this->id);
            if ($editEmpPrincipal->getResult()) {
                $this->data['form'] = $editEmpPrincipal->getResultBd();
                $this->viewEditEmpPrincipal();
            } else {
                $urlRedirect = URLADM . "list-emp-principal/index";
                header("Location: $urlRedirect");
            }
        } else {
            $this->editEmpPrincipal();
        }
    }

    /**
     * Instanciar a MODELS e o método "listSelect" responsável em buscar os dados para preencher o campo SELECT 
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditEmpPrincipal(): void
    {
        $button = ['list_emp_principal' => ['menu_controller' => 'list-emp-principal', 'menu_metodo' => 'index']];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listSelect = new \App\adms\Models\AdmsEditEmpPrincipal();
        $this->data['select'] = $listSelect->listSelect();

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['sidebarActive'] = "list-emp-principal";
        $loadView = new \Core\ConfigView("adms/Views/empresas/editEmpPrincipal", $this->data);
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
    private function editEmpPrincipal(): void
    {
        if (!empty($this->dataForm['SendEditEmpPrincipal'])) {
            unset($this->dataForm['SendEditEmpPrincipal']);
            $editEmpresas = new \App\adms\Models\AdmsEditEmpPrincipal();
            $editEmpresas->update($this->dataForm);

            if ($editEmpresas->getResult()) {
                $urlRedirect = URLADM . "list-emp-principal/index/" . $this->dataForm['id'];
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditEmpPrincipal();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Empresa não encontrada!</p>";
            $urlRedirect = URLADM . "list-emp-principal/index";
            header("Location: $urlRedirect");
        }
    }
}
