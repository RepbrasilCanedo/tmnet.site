<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Visualizar detalhes da página no banco de dados
 *
 * @author Daniel Canedo - docan2006@gmail.com
 */
class AdmsViewProd
{

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result = false;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var int|string|null $id Recebe o id do registro */
    private int|string|null $id;    
    
    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /** @var array|null $file Recebe o arquivo (PDF) para upload */
    private array|null $file; // <--- ADICIONE ESTA LINHA AQUI


    /** @var array|null $data Recebe as informações do formulário */
    private array|null $listRegistryAdd;

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
     * Metodo para visualizar os detalhes da página
     */
    public function viewProd(int $id): void
    {
        $this->id = $id;
        $_SESSION['produto']='';
        $_SESSION['produto']= $this->id;

        $viewProd = new \App\adms\Models\helper\AdmsRead();
        $viewProd->fullRead("SELECT prod.id as id_prod, prod.name as name_prod, typ.name as name_type, prod.serie as serie_prod, 
                prod.modelo_id as name_modelo, prod.marca_id as name_mar, clie.razao_social as razao_social_clie, clie.nome_fantasia as nome_fantasia_clie, prod.venc_contr as venc_contr_prod, 
                contr.name as name_contr_id, prod.dias, prod.inicio_contr, prod.inf_adicionais as inf_adicionais, sit.name as name_sit, prod.created, prod.modified 
                FROM adms_produtos AS prod  
                INNER JOIN adms_type_equip AS typ ON typ.id=prod.type_id 
                INNER JOIN adms_clientes AS clie ON clie.id=prod.cliente_id 
                INNER JOIN adms_sit_equip AS sit ON sit.id=prod.sit_id                
                INNER JOIN adms_contr AS contr ON contr.id=prod.contr_id
                WHERE prod.id= :prod_id", "prod_id={$this->id}");


        $this->resultBd = $viewProd->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Produto não encontrado!</p>";
            $this->result = false;
        }
    }
    

    public function listTable(): array
    {

        $listTable = new \App\adms\Models\helper\AdmsRead();
        // Mantido original (já traz cham.id)
        $listTable->fullRead("SELECT cham.id, sta.name as nome_sta, cham.dt_status as dt_status_cham,  usr.name as name_usr, cham.inf_cham as inf_cham
        FROM adms_cham AS cham        
        INNER JOIN adms_cham_status AS sta ON sta.id=cham.status_id  
        INNER JOIN adms_users AS usr ON usr.id=cham.suporte_id 
        WHERE cham.prod_id= :prod_id", "prod_id={$_SESSION['produto']}");

        $registry['listTable'] = $listTable->getResult();

        $this->listRegistryAdd = ['listTable' => $registry['listTable']];

        return $this->listRegistryAdd;
    }

    // Adicione esses métodos na classe AdmsViewProd

public function uploadLaudo(array $data, array $file): bool
{
    $this->data = $data;
    $this->file = $file;

    if ($this->file['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: Falha no envio do arquivo.</div>";
        return false;
    }

    if ($this->file['type'] !== 'application/pdf') {
        $_SESSION['msg'] = "<div class='alert alert-danger'>Erro: O arquivo deve ser um PDF!</div>";
        return false;
    }

    // 1. Definir diretório
    $diretorio = "app/adms/assets/arquivos/produtos/" . $this->data['produto_id'] . "/";
    if (!file_exists($diretorio) && !is_dir($diretorio)) {
        mkdir($diretorio, 0755, true);
    }

    // 2. CRIAR NOME ESPECÍFICO E CURTO
    // Exemplo de resultado: laudo_prod_15_1707572400.pdf
    $extensao = pathinfo($this->file['name'], PATHINFO_EXTENSION);
    $nome_limpo = "laudo_prod_" . $this->data['produto_id'] . "_" . time() . "." . $extensao;
    
    $destino = $diretorio . $nome_limpo;

    if (move_uploaded_file($this->file['tmp_name'], $destino)) {
        $save = new \App\adms\Models\helper\AdmsCreate();
        
        // Salvamos o nome limpo no banco
        $dataSave['nome_arquivo'] = $nome_limpo; 
        $dataSave['caminho'] = $destino;
        $dataSave['produto_id'] = $this->data['produto_id'];
        $dataSave['created'] = date("Y-m-d H:i:s");
        
        $save->exeCreate("adms_laudos_produtos", $dataSave);
        if ($save->getResult()) {
            $_SESSION['msg'] = "<div class='alert alert-success'>Laudo arquivado como: $nome_limpo</div>";
            return true;
        }
    }
    
    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao processar upload.</div>";
    return false;
}

public function listLaudos(int $id): array|null
{
    $list = new \App\adms\Models\helper\AdmsRead();
    $list->fullRead("SELECT id, nome_arquivo, caminho, created FROM adms_laudos_produtos WHERE produto_id = :prod_id ORDER BY id DESC", "prod_id={$id}");
    return $list->getResult();
}

public function deleteLaudo(int $id_laudo): bool
{
    // 1. Buscar os dados do laudo para saber o caminho do arquivo
    $viewLaudo = new \App\adms\Models\helper\AdmsRead();
    $viewLaudo->fullRead("SELECT caminho FROM adms_laudos_produtos WHERE id = :id LIMIT 1", "id={$id_laudo}");
    $res = $viewLaudo->getResult();

    if ($res) {
        $caminhoArquivo = $res[0]['caminho'];

        // 2. Deletar do Banco de Dados
        $delete = new \App\adms\Models\helper\AdmsDelete();
        $delete->exeDelete("adms_laudos_produtos", "WHERE id = :id", "id={$id_laudo}");

        if ($delete->getResult()) {
            // 3. Deletar o arquivo físico da pasta
            if (file_exists($caminhoArquivo)) {
                unlink($caminhoArquivo);
            }
            $_SESSION['msg'] = "<div class='alert alert-success'>Laudo excluído com sucesso!</div>";
            return true;
        }
    }

    $_SESSION['msg'] = "<div class='alert alert-danger'>Erro ao excluir o laudo.</div>";
    return false;
}

}