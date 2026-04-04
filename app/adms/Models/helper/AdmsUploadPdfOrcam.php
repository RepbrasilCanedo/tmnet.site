<?php

namespace App\adms\Models\helper;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe gernérica para redimensionar a image
 *
  * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsUploadPdfOrcam
{
    /** @var array $imageData Recebe a informação da imagem*/
    private array $imageData;
    /** @var string $directory Recebe o caminho do diretorio*/
    private string $directory;    
    /** @var string $name Recebe o nome da imagem*/
    private string $name;
    /** @var [type] $newImage Recebe o nome temporario da imagem*/
    private $newImage;
    /** @var boolean $result Recebe o resultado TRUE ou FALSE*/
    private bool $result;

    /** @return boolean Recebe o resultado TRUE ou FALSE*/
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Recebe as informações para fazer o upload do pdf
     * Chama o metodo valDirectory para validar o diretorio
     * @param array $imageData
     * @param string $directory
     * @param string $name
     * @param integer $width
     * @param integer $height
     * @return void
     */
    public function upload(array $imageData, string $directory, string $name): void
    {
        $this->imageData = $imageData;
        $this->directory = $directory;
        $this->name = $name;

        //var_dump($this->imageData);

        $this->valDirectory();
    }

    /**
     * Metodo faz a verificação se o diretorio existe
     * Se o diretorio não existir chama o metodo createDir para criar o diretorio
     * Se o diretorio existir chama o metodo uploadFile para prossegir com o upload
     * @return void
     */
    private function valDirectory(): void
    {
        if ((file_exists($this->directory)) and (!is_dir($this->directory))) {
            $this->createDir();
        } elseif (!file_exists($this->directory)) {
            $this->createDir();
        } else {
            $this->uploadFile();
        }
    }

    /**
     * Metodo tenta criar o diretorio e verifica se o mesmo existe
     * Caso o diretorio exista prossegue com o upload
     * Se não existir retorna FALSE
     * @return void
     */
    private function createDir(): void
    {
        mkdir($this->directory, 0755, true);
        if (!file_exists($this->directory)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem em PDF não realizada com sucesso. Tente novamente!</p>";
            $this->result = false;
        } else {
            $this->uploadFile();
        }
    }

    /**
     * Metodo verifica o tipo da imagem em PDF
     * Chama o metodo uploadFileJpeg caso a imagem seja JPEG
     * Chama o metodo uploadFilePng caso a imagem seja PNG
     * Retorna FALSE se houver algum erro
     * @return void
     */
    private function uploadFile(): void
    {

        switch ($this->imageData['type']) {           
            
            case 'application/pdf':
                $this->uploadFilePdf();
                break;
            default:
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar arquivo em PDF!</p>";
                $this->result = false;
        }
    }

    /**
     * Metodo faz o upload da imagem JPEG
     * Recebe o caminho do diretorio, nome da imagem e as dimensões
     * Retorna FALSE se houver erro
     * @return void
     */
    private function uploadFilePdf(): void
    {
        $this->newImage = ($this->imageData['tmp_name']);

        $filename= $this->name;
        $destination= $this->directory;
        $destino=$destination . $filename;
        // Enviar a imagem para servidor
        if (move_uploaded_file($_FILES['new_image_orcam']['tmp_name'], $destino)) {
            $_SESSION['msg'] = "<p class='alert-success'>Upload da imagem em PDF realizada com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem em PDF não realizada com sucesso. Tente novamente!</p>";
            $this->result = false;
        }
    }
}
