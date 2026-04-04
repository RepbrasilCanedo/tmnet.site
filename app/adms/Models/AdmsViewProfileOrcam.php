<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){ header("Location: /"); die(); }

class AdmsViewProfileOrcam
{
    private bool $result = false;
    private array|null $resultBd;
    private $id;
    private array|null $data;
    private array|null $dataImagem;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewProfileOrcam(int $id): void
    {
        $this->id = $id;
        $viewProfileOrcam = new \App\adms\Models\helper\AdmsRead();
        $viewProfileOrcam->fullRead("SELECT id, status_id, prod_serv, info_prod_serv, image FROM adms_orcam WHERE id= :id", "id={$this->id}");
        $this->resultBd = $viewProfileOrcam->getResult();
        $this->result = (bool)$this->resultBd;
    }

    public function update(array $data): void
    {
        $this->data = $data; // Contém o ID vindo do campo hidden
        $this->dataImagem = $_FILES['new_image_orcam'] ?? null;

        if (!empty($this->dataImagem['name'])) {
            $this->valInput();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar um arquivo PDF!</p>";
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valExt = new \App\adms\Models\helper\AdmsValExtPdf();
        $valExt->validateExtOrcam($this->dataImagem['type']);

        if ($valExt->getResult()) {
            $this->upload();
        } else {
            $this->result = false;
        }
    }

    private function upload(): void
    {
        $slug = new \App\adms\Models\helper\AdmsSlug();
        $nameImg = $slug->slug($this->dataImagem['name']);
        $directory = "app/adms/assets/image/orcamentos/" . $this->data['id'] . "/";

        $upload = new \App\adms\Models\helper\AdmsUploadPdfOrcam();
        $upload->upload($this->dataImagem, $directory, $nameImg);

        if ($upload->getResult()) {
            $this->saveDb($nameImg);
        } else {
            $this->result = false;
        }
    }

    private function saveDb(string $nameImg): void
    {
        // Define o status: se era reprovada (5), vira reenviada (6), senão enviada (3)
        $statusId = ($this->resultBd[0]['status_id'] == 5) ? 6 : 3;

        $updateData = [
            'image' => $nameImg,
            'status_id' => $statusId,
            'modified' => date("Y-m-d H:i:s")
        ];

        $up = new \App\adms\Models\helper\AdmsUpdate();
        $up->exeUpdate("adms_orcam", $updateData, "WHERE id=:id", "id={$this->data['id']}");

        if ($up->getResult()) {
            // Apaga o arquivo antigo se existir
            if (!empty($this->resultBd[0]['image']) && $this->resultBd[0]['image'] != $nameImg) {
                $oldFile = "app/adms/assets/image/orcamentos/" . $this->data['id'] . "/" . $this->resultBd[0]['image'];
                if (file_exists($oldFile)) { unlink($oldFile); }
            }
            $_SESSION['msg'] = "<p class='alert-success'>Orçamento anexado e status atualizado com sucesso!</p>";
            $this->result = true;
        } else {
            $this->result = false;
        }
    }
}