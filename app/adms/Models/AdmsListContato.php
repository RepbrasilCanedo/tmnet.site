<?php

namespace App\adms\Models;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsListcontato
{
    private bool $result;
    private array|null $resultBd;
    private int $page;
    private int $limitResult = 40;
    private string|null $resultPg;

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }
    function getResultPg(): string|null { return $this->resultPg; }

    public function listContato(int $page): void
    {
        $this->page = (int) $page ? $page : 1;
        $userLevel = (int)$_SESSION['adms_access_level_id'];
        $empId = (int)$_SESSION['emp_user'];
        $userId = (int)$_SESSION['user_id'];

        // 1. Definição dos Filtros (Lógica de Segurança)
        $where = " WHERE 1=1";
        $bindValues = "";

        if ($userLevel > 2) {
            $where .= " AND mens.empresa_id = :empresa_id";
            $bindValues = "empresa_id={$empId}";

            if ($userLevel == 12) {
                // Verifica se este usuário logado possui vínculos
                $readVinculo = new \App\adms\Models\helper\AdmsRead();
                $readVinculo->fullRead("SELECT id FROM adms_user_clie WHERE user_id = :u_id", "u_id={$userId}");
                
                if ($readVinculo->getResult()) {
                    // CASO A: Usuário tem vínculo -> Vê apenas os dele
                    $where .= " AND mens.cliente_id IN (SELECT cliente_id FROM adms_user_clie WHERE user_id = {$userId})";
                } else {
                    // CASO B: Usuário órfão -> Vê o que não é de ninguém (trava de exclusão)
                    $where .= " AND mens.cliente_id NOT IN (SELECT cliente_id FROM adms_user_clie)";
                }
            }
        }

        // 2. Paginação
        $pagination = new \App\adms\Models\helper\AdmsPagination(URLADM . 'list-contato/index');
        $pagination->condition($this->page, $this->limitResult);
        $pagination->pagination("SELECT COUNT(mens.id) AS num_result FROM sts_contacts_msgs mens $where", $bindValues);
        $this->resultPg = $pagination->getResult();

        // 3. Leitura dos Dados
        $listRead = new \App\adms\Models\helper\AdmsRead();
        $query = "SELECT mens.id as id_mens, mens.empresa_id, clie.nome_fantasia as nome_fantasia_clie, 
                 mens.assunto as assunto_mens, mens.nome as nome_mens, mens.email as email_mens, 
                 mens.tel as tel_mens, mens.mensagem as mensagem_mens, mens.dia as dia_mens, 
                 mens.status as status_mens
          FROM sts_contacts_msgs AS mens
          INNER JOIN adms_clientes AS clie ON clie.id = mens.cliente_id 
          $where 
          ORDER BY FIELD(mens.status, 'Enviado', 'Lido', 'Respondido'), mens.id DESC 
          LIMIT :limit OFFSET :offset";

        $listRead->fullRead($query, "{$bindValues}&limit={$this->limitResult}&offset={$pagination->getOffset()}");

        $this->resultBd = $listRead->getResult();

        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>Nenhuma mensagem encontrada para o seu nível de acesso!</div>";
            $this->result = false;
        }
    }
}