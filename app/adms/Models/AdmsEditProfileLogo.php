<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditProfileLogo
{
    private array|null $data;
    private array|null $dataImagem;
    private string|null $nameImg;
    private string|null $directory;
    private string|null $delImg;

    private bool $result = false;
    private array|null $resultBd;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewProfileLogo(): bool
    {
        $viewLogo = new \App\adms\Models\helper\AdmsRead();
        $viewLogo->fullRead("SELECT id, logo, modified FROM adms_emp_principal WHERE id=:id LIMIT :limit", "id={$_SESSION['emp_logo']}&limit=1");

        $this->resultBd = $viewLogo->getResult();
        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Cliente não encontrado!</p>";
            $this->result = false;
            return false;
        }
    }

    public function update(array $data): void
    {
        $this->data = $data;

        $this->dataImagem = $this->data['new_image'];
        unset($this->data['new_image']);

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            if (!empty($this->dataImagem['name'])) {
                $this->valInput();
            } else {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar uma imagem!</p>";
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valExtImg = new \App\adms\Models\helper\AdmsValExtImg();
        $valExtImg->validateExtImg($this->dataImagem['type']);

        if (($this->viewProfileLogo()) and ($valExtImg->getResult())) {
            $this->upload();
        } else {
            $this->result = false;
        }
    }

    private function upload(): void
    {
        $slugImg = new \App\adms\Models\helper\AdmsSlug();
        $this->nameImg = $slugImg->slug($this->dataImagem['name']);

        // Define a pasta exata da empresa que estamos a editar
        $empresaId = (int)$_SESSION['emp_logo'];
        $this->directory = "app/adms/assets/image/logo/clientes/" . $empresaId . "/";
        
        // ========================================================================
        // DOCAN FIX: Criar a pasta fisicamente no servidor com permissões máximas
        // ========================================================================
        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }

        $uploadImgRes = new \App\adms\Models\helper\AdmsUploadImgRes();
        $uploadImgRes->upload($this->dataImagem, $this->directory, $this->nameImg, 300, 300);

        if ($uploadImgRes->getResult()) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');

        $this->data['logo'] = $this->nameImg;
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upLogo = new \App\adms\Models\helper\AdmsUpdate();
        $upLogo->exeUpdate("adms_emp_principal", $this->data, "WHERE id=:id", "id={$_SESSION['emp_logo']}");
        
        if ($upLogo->getResult()) {            
            $this->deleteImage();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Logo não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    private function deleteImage(): void
    {
        // Se já existia uma imagem no Banco de Dados e ela é diferente da nova, apaga a velha!
        if (!empty($this->resultBd[0]['logo']) && $this->resultBd[0]['logo'] !== $this->nameImg) {
            $empresaId = (int)$_SESSION['emp_logo'];
            $this->delImg = "app/adms/assets/image/logo/clientes/" . $empresaId . "/" . $this->resultBd[0]['logo'];
            
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Logo do Clube atualizada com sucesso!</p>";
        $this->result = true;
    }
}