<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}
/**
 * Editar o Logo do contrato
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditProfileLogo
{
    private array|null $data;
    private array|null $dataImagem;
    private string|null $nameImg;
    private string|null $directory;
    private string|null $delImg;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * @return bool Retorna os detalhes do registro
     */
    function getResultBd(): array|null
    {
        return $this->resultBd;
    }

    /**
     * Metodo para pesquisar a imagem do usuário que será editada
     * @return boolean
     */
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

    /**
     * Metodo recebe a informação da imagem que será editada
     * Instancia o helper AdmsValEmptyField para validar os campos do formulário
     * Retira o campo "new_image" da validação
     * Chama o metodo valInput para validar a extensão da imagem     
     * @param array|null $data
     * @return void
     */
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

    /** 
     * Valida a extensão da imagem com o helper AdmsValExtImg
     * Retorna FALSE quando houve algum erro
     * 
     * @return void
     */
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

    /**
     * Metodo gera o slug da imagem com o helper AdmsSlug
     * Faz o upload da imagem usando o helper AdmsUploadImgRes
     * Chama o metodo edit para atualizar as informações no banco de dados
     * @return void
     */
    private function upload(): void
    {
        $slugImg = new \App\adms\Models\helper\AdmsSlug();
        $this->nameImg = $slugImg->slug($this->dataImagem['name']);

        $this->directory = "app/adms/assets/image/logo/clientes/" . $_SESSION['emp_logo'] . "/";
        
        
        $uploadImgRes = new \App\adms\Models\helper\AdmsUploadImgRes();
        $uploadImgRes->upload($this->dataImagem, $this->directory, $this->nameImg, 300, 300);

        if ($uploadImgRes->getResult()) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo envia as informações editadas para o banco de dados
     * Chama o metodo deleteImage apagar a imagem antiga do usuário
     *
     * @return void
     */
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

    /**
     * Metodo apaga a imagem antiga do usuário
     * @return void
     */
    private function deleteImage(): void
    {
        if (((!empty($this->resultBd[0]['logo'])) or ($this->resultBd[0]['logo'] != null)) and ($this->resultBd[0]['logo'] != $this->nameImg)) {
            $this->delImg = "app/adms/assets/image/logo/clientes/". $_SESSION['emp_logo']. "/" . $this->resultBd[0]['logo'];
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Logo editada com sucesso!</p>";
        $this->result = true;
    }
}
