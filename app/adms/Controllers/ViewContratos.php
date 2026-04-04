<?php

namespace App\adms\Controllers;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ViewContratos
{
    private array|string|null $data = [];
    private int|string|null $id;

    public function index(int|string|null $id = null): void
    {
        $this->id = (int) $id;

        if (!empty($this->id)) {

            // INTERCEPTADOR DE UPLOAD DE ANEXO
            $dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            if (!empty($dataForm['SendAddAnexo'])) {
                $this->processaUploadAnexo($this->id);
            }

            // FLUXO NORMAL DE VISUALIZAÇÃO
            $viewContrato = new \App\adms\Models\AdmsViewContratos();
            $viewContrato->viewContrato($this->id);
            
            if ($viewContrato->getResult()) {
                $this->data['viewContrato'] = $viewContrato->getResultBd()[0];
                $this->data['viewAnexos'] = $viewContrato->getResultAnexos(); // Envia os anexos para a View
                $this->viewInfoContrato();
            } else {
                $urlRedirect = URLADM . "list-contratos/index";
                header("Location: $urlRedirect");
                exit;
            }
        } else {
            $urlRedirect = URLADM . "list-contratos/index";
            header("Location: $urlRedirect");
            exit;
        }
    }

    /**
     * Lógica isolada para processar o Upload de PDF
     */
    private function processaUploadAnexo(int $cont_id): void
    {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            
            $file = $_FILES['image'];
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            // Validação de Segurança: Apenas PDF
            if ($fileExtension !== 'pdf') {
                $_SESSION['msg'] = "<p class='alert-warning'>Aviso: O sistema aceita apenas arquivos no formato PDF.</p>";
                return;
            }

            // Cria um nome único para não sobrescrever arquivos com o mesmo nome
            $fileName = time() . '_' . md5($file['name']) . '.pdf';
            
            // Define o diretório (ex: app/adms/assets/arquivos/contratos/12/)
            $directory = "app/adms/assets/arquivos/contratos/" . $cont_id . "/";
            
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true); // Cria a pasta se não existir
            }

            // Move o arquivo da pasta temporária para a pasta final
            if (move_uploaded_file($file['tmp_name'], $directory . $fileName)) {
                
                // Grava no Banco de Dados
                $dataInsert = [
                    'cont_id' => $cont_id,
                    'image' => $fileName,
                    'created' => date("Y-m-d H:i:s")
                ];

                $createAnexo = new \App\adms\Models\helper\AdmsCreate();
                $createAnexo->exeCreate("adms_contr_anexos", $dataInsert);

                if ($createAnexo->getResult()) {
                    $_SESSION['msg'] = "<p class='alert-success'>Arquivo PDF anexado com sucesso!</p>";
                } else {
                    $_SESSION['msg'] = "<p class='alert-danger'>Erro ao salvar registro do anexo no banco de dados.</p>";
                }
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Não foi possível realizar o upload do arquivo físico.</p>";
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-warning'>Aviso: Selecione um arquivo válido antes de enviar.</p>";
        }

        // Dá um refresh na página para limpar o POST e exibir a mensagem
        $urlRedirect = URLADM . "view-contratos/index/" . $cont_id;
        header("Location: $urlRedirect");
        exit;
    }

    private function viewInfoContrato(): void
    {
        $button = [
            'list_contratos' => ['menu_controller' => 'list-contratos', 'menu_metodo' => 'index'],
            'edit_contratos' => ['menu_controller' => 'edit-contratos', 'menu_metodo' => 'index'],
            'delete_contratos' => ['menu_controller' => 'delete-contratos', 'menu_metodo' => 'index']
        ];
        
        $listBotton = new \App\adms\Models\helper\AdmsButton();
        $this->data['button'] = $listBotton->buttonPermission($button);

        $listMenu = new \App\adms\Models\helper\AdmsMenu();
        $this->data['menu'] = $listMenu->itemMenu(); 
        
        $this->data['sidebarActive'] = "list-contratos"; 
        
        $loadView = new \Core\ConfigView("adms/Views/contratos/viewContratos", $this->data);
        $loadView->loadView();
    }
}