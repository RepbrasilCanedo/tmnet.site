<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Model para visualizar detalhes do atleta e gerir upload de foto com redimensionamento
 * * @author Daniel Canedo
 */
class AdmsViewAtleta
{
    private array|null $result;
    private bool $resultUpdate;

    /**
     * @return array|null Retorna os detalhes do atleta
     */
    function getResult(): array|null
    {
        return $this->result;
    }

    /**
     * Procura os detalhes de um atleta específico no banco de dados
     * @param int $id
     * @return void
     */
    public function viewAtleta(int $id): void
    {
        $viewAtleta = new \App\adms\Models\helper\AdmsRead();
        $viewAtleta->fullRead("SELECT * FROM adms_atletas WHERE id=:id LIMIT :limit", "id={$id}&limit=1");
        $this->result = $viewAtleta->getResult();
    }

    /**
     * Gere o processo de upload, redimensionamento e atualização da foto
     * @param int $id ID do atleta
     * @param array $dataImage Dados vindos de $_FILES['imagem']
     * @return void
     */
    public function uploadFotoAtleta(int $id, array $dataImage): void
{
    // 1. Definições de tamanho (Isso resolve o problema da foto "muito grande")
    $largura = 300; 
    $altura = 300;  
    
    // 2. Caminho Físico (Para o PHP salvar o ficheiro)
    // Note que usei "image" conforme o seu diretório atual
    $diretorio = "app/adms/assets/image/atletas/";
    
    // 3. Verifica se o diretório existe e tem permissão de escrita
    if (!is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    $this->viewAtleta($id);
    $atleta = $this->result[0];
    $fotoAntiga = $atleta['imagem'] ?? null;

    // 4. Gera o nome do ficheiro
    $extensao = ($dataImage['type'] == 'image/png') ? '.png' : '.jpg';
    $nomeNovaFoto = "atleta_" . $id . "_" . time() . $extensao;

    // 5. Instancia a Helper
    $uploadImgRes = new \App\adms\Models\helper\AdmsUploadImgRes();
    
    // IMPORTANTE: Passamos o array da imagem inteiro ($dataImage)
    $uploadImgRes->upload($dataImage, $diretorio, $nomeNovaFoto, $largura, $altura);

    if ($uploadImgRes->getResult()) {
        // 6. SÓ grava no banco se o ficheiro físico foi criado com sucesso
        $this->saveFotoAtleta($id, $nomeNovaFoto);

        if ($this->resultUpdate) {
            // Apaga a antiga se não for a padrão
            if (!empty($fotoAntiga) && $fotoAntiga != "atleta_padrao.png") {
                if (file_exists($diretorio . $fotoAntiga)) {
                    unlink($diretorio . $fotoAntiga);
                }
            }
        }
    } else {
        $this->resultUpdate = false;
        // Caso a helper não defina a mensagem, definimos aqui
        if(!isset($_SESSION['msg'])) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Falha ao processar redimensionamento.</p>";
        }
    }
}

    /**
     * Atualiza o campo 'imagem' na tabela adms_atletas
     * @param int $id
     * @param string $nomeNovaFoto
     * @return void
     */
    private function saveFotoAtleta(int $id, string $nomeNovaFoto): void
    {
        $dataUpdate['imagem'] = $nomeNovaFoto;
        $dataUpdate['modified'] = date("Y-m-d H:i:s");

        $upAtleta = new \App\adms\Models\helper\AdmsUpdate();
        $upAtleta->exeUpdate("adms_atletas", $dataUpdate, "WHERE id=:id", "id={$id}");

        if ($upAtleta->getResult()) {
            $this->resultUpdate = true;
        } else {
            $this->resultUpdate = false;
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Foto carregada, mas não foi possível atualizar o registo no banco de dados!</p>";
        }
    }
}