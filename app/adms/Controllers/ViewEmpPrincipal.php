<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewEmpPrincipal
{
    private array|string|null $data;
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        if (!empty($id)) {
            $this->id = (int) $id;
            $viewEmpPrincipal = new \App\adms\Models\AdmsViewEmpPrincipal();
            $viewEmpPrincipal->viewEmpPrincipal($this->id);

            if ($viewEmpPrincipal->getResult()) {
                $this->data['viewEmpPrincipal'] = $viewEmpPrincipal->getResultBd();
                $this->viewEmpPrincipal();
            } else {
                $urlRedirect = URLADM . "view-emp-principal/index";
                header("Location: $urlRedirect");
                exit; // DOCAN FIX
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Dados da Empresa não encontrado!</p>";
            $urlRedirect = URLADM . "list-emp-principal/index";
            header("Location: $urlRedirect");
            exit; // DOCAN FIX
        }
    }

    private function viewEmpPrincipal(): void
    {
        $button = [
            'edit_emp_principal' => ['menu_controller' => 'edit-emp-principal', 'menu_metodo' => 'index'],
            'list_emp_principal' => ['menu_controller' => 'list-emp-principal', 'menu_metodo' => 'index'],
            'edit_profile_logo' => ['menu_controller' => 'edit-profile-logo', 'menu_metodo' => 'index']
        ];
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu();

        $this->data['sidebarActive'] = "view-emp-principal";
        $loadView = new \Core\ConfigView("adms/Views/empresas/viewEmpPrincipal", $this->data);
        $loadView->loadView();
    }
}