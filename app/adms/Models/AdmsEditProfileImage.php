<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Editar a imagem do perfil do usuario
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsEditProfileImage
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

    public function viewProfile(): bool
    {
        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT id, imagem FROM adms_users WHERE id=:id LIMIT :limit", "id=" . $_SESSION['user_id'] . "&limit=1");

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
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
        
        if (($this->viewProfile()) and ($valExtImg->getResult())) {
            $this->upload();
        } else {
            $this->result = false;
        }
    }

    // ========================================================================
    // DOCAN FIX: Função para ler o EXIF do telemóvel e girar a foto
    // ========================================================================
    private function fixExifRotation(string $filePath, string $mimeType): void
    {
        if (!function_exists('exif_read_data')) {
            return;
        }

        // As fotos de telemóvel vêm sempre em JPEG
        if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg' || $mimeType === 'image/pjpeg') {
            $exif = @exif_read_data($filePath);
            
            if (!empty($exif['Orientation'])) {
                $image = @imagecreatefromjpeg($filePath);
                if ($image) {
                    $rotated = false;
                    switch ($exif['Orientation']) {
                        case 3:
                            $image = imagerotate($image, 180, 0);
                            $rotated = true;
                            break;
                        case 6:
                            // Modo retrato tradicional (gira -90 graus)
                            $image = imagerotate($image, -90, 0);
                            $rotated = true;
                            break;
                        case 8:
                            $image = imagerotate($image, 90, 0);
                            $rotated = true;
                            break;
                    }
                    // Se foi girada, salva por cima do ficheiro temporário
                    if ($rotated) {
                        imagejpeg($image, $filePath, 100);
                    }
                    imagedestroy($image);
                }
            }
        }
    }

    private function upload(): void
    {
        $slugImg = new \App\adms\Models\helper\AdmsSlug();
        $this->nameImg = $slugImg->slug($this->dataImagem['name']);

        $this->directory = "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/";
        
        if (!file_exists($this->directory)) {
            mkdir($this->directory, 0777, true);
        }

        // DOCAN FIX: Arruma a foto deitada ANTES de cortar
        $this->fixExifRotation($this->dataImagem['tmp_name'], $this->dataImagem['type']);

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
        $this->data['imagem'] = $this->nameImg;
        $this->data['modified'] = date("Y-m-d H:i:s");

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id=" . $_SESSION['user_id']);

        if ($upUser->getResult()) {
            $_SESSION['user_image'] = $this->nameImg;
            $this->deleteImage();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Imagem não editada com sucesso!</p>";
            $this->result = false;
        }
    }

    private function deleteImage(): void
    {
        if (((!empty($this->resultBd[0]['imagem'])) or ($this->resultBd[0]['imagem'] != null)) and ($this->resultBd[0]['imagem'] != $this->nameImg)) {
            $this->delImg = "app/adms/assets/image/users/" . $_SESSION['user_id'] . "/" . $this->resultBd[0]['imagem'];
            if (file_exists($this->delImg)) {
                unlink($this->delImg);
            }
        }

        $_SESSION['msg'] = "<p class='alert-success'>Imagem atualizada com sucesso!</p>";
        $this->result = true;
    }
}