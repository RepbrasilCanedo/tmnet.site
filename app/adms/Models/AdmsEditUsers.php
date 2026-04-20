<?php

namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsEditUsers
{
    private bool $result = false;
    private array|null $resultBd;
    private int|string|null $id;
    private array|null $data;
    private array|null $listRegistryAdd;
    
    private int $statusAnterior = 0; 

    function getResult(): bool { return $this->result; }
    function getResultBd(): array|null { return $this->resultBd; }

    public function viewUser(int $id): void
    {
        $this->id = $id;

        $viewUser = new \App\adms\Models\helper\AdmsRead();
        $viewUser->fullRead("SELECT usr.id, usr.name, usr.apelido, usr.data_nascimento, usr.mao_dominante, usr.estilo_jogo, usr.sexo, usr.email, usr.telefone, usr.empresa_id, usr.user, 
                            usr.adms_sits_user_id, usr.adms_access_level_id, usr.pontuacao_ranking FROM adms_users AS usr
                            INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                            WHERE usr.id=:id AND lev.order_levels >:order_levels
                            LIMIT :limit",
                            "id={$this->id}&order_levels=" . $_SESSION['order_levels'] . "&limit=1"
        );

        $this->resultBd = $viewUser->getResult();
        if ($this->resultBd) {
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Usuário não encontrado!</p>";
            $this->result = false;
        }
    }

    public function update(array $data): void
    {
        $this->data = $data; 
        
        $readStatus = new \App\adms\Models\helper\AdmsRead();
        $readStatus->fullRead("SELECT adms_sits_user_id FROM adms_users WHERE id=:id LIMIT 1", "id={$this->data['id']}");
        if ($readStatus->getResult()) {
            $this->statusAnterior = (int)$readStatus->getResult()[0]['adms_sits_user_id'];
        }

        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    private function valInput(): void
    {
        $valEmail = new \App\adms\Models\helper\AdmsValEmail();
        $valEmail->validateEmail($this->data['email']);

        $valEmailSingle = new \App\adms\Models\helper\AdmsValEmailSingle();
        $valEmailSingle->validateEmailSingle($this->data['email'], true, $this->data['id']);

        $valUserSingle = new \App\adms\Models\helper\AdmsValUserSingle();
        $valUserSingle->validateUserSingle($this->data['user'], true, $this->data['id']);

        if (($valEmail->getResult()) and ($valEmailSingle->getResult()) and ($valUserSingle->getResult())) {
            $this->edit();
        } else {
            $this->result = false;
        }
    }

    private function edit(): void
    {
        date_default_timezone_set('America/Bahia');
        $this->data['modified'] = date("Y-m-d H:i:s");
        
        // ========================================================================
        // DOCAN FIX: LÓGICA DE FILIAÇÃO (N:N) NO EDITAR
        // ========================================================================
        $clubeId = 1;
        if ($_SESSION['adms_access_level_id'] > 2) {
            $clubeId = $_SESSION['emp_user']; // Clube editando
        } elseif (!empty($this->data['empresa_id'])) {
            $clubeId = $this->data['empresa_id']; // S-Admin editando (escolheu no select)
        }

        // Se for Atleta (14), ele pertence à TMNet (1). Se for gestor, pertence ao Clube ($clubeId)
        if (isset($this->data['adms_access_level_id']) && $this->data['adms_access_level_id'] == 14) {
            $this->data['empresa_id'] = 1;
        } else {
            $this->data['empresa_id'] = $clubeId;
        }

        $userId = $this->data['id'];

        $upUser = new \App\adms\Models\helper\AdmsUpdate();
        $upUser->exeUpdate("adms_users", $this->data, "WHERE id=:id", "id={$userId}");

        if ($upUser->getResult()) {
            $_SESSION['msg'] = "<p class='alert-success'>Usuário editado com sucesso!</p>";
            $this->result = true;

            // Insere o atleta na tabela ponte se a relação ainda não existir
            if (isset($this->data['adms_access_level_id']) && $this->data['adms_access_level_id'] == 14 && $clubeId != 1) {
                $readRel = new \App\adms\Models\helper\AdmsRead();
                $readRel->fullRead("SELECT id FROM adms_atleta_clube WHERE adms_user_id = :u AND empresa_id = :e LIMIT 1", "u={$userId}&e={$clubeId}");
                
                if (!$readRel->getResult()) {
                    $dadosClubeAtleta = [
                        'adms_user_id' => $userId,
                        'empresa_id' => $clubeId,
                        'created' => date("Y-m-d H:i:s")
                    ];
                    $createRel = new \App\adms\Models\helper\AdmsCreate();
                    $createRel->exeCreate("adms_atleta_clube", $dadosClubeAtleta);
                }
            }

            $novoStatus = (int)$this->data['adms_sits_user_id'];
            if ($this->statusAnterior === 3 && $novoStatus === 1) {
                $this->gerarBotaoWhatsAppBoasVindas();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Nenhuma alteração foi feita no usuário.</p>";
            $this->result = false;
        }
    }

    private function gerarBotaoWhatsAppBoasVindas(): void
    {
        $telefoneLimpo = preg_replace('/\D/', '', $this->data['telefone']);
        if (strlen($telefoneLimpo) < 10) return; 
        if (substr($telefoneLimpo, 0, 2) !== '55') $telefoneLimpo = '55' . $telefoneLimpo;

        $nomeAtleta = $this->data['name'];
        $userAcesso = $this->data['user'];
        $urlSistema = URLADM . "login/index";

        $mensagem = "🏓 *Olá, {$nomeAtleta}!* 🎉\n\n";
        $mensagem .= "O seu credenciamento no *TMNet* foi *APROVADO* com sucesso!\n\n";
        $mensagem .= "🔐 *Seu Login:* {$userAcesso}\n";
        $mensagem .= "🌐 *Acessar:* {$urlSistema}\n\n";
        $mensagem .= "_Prepare a sua raquete!_ 🏆";

        $mensagemCodificada = urlencode($mensagem);
        $linkWhats = "https://api.whatsapp.com/send?phone={$telefoneLimpo}&text={$mensagemCodificada}";

        $_SESSION['msg'] .= "<div style='margin-top: 15px; text-align: center;'>
                                <a href='{$linkWhats}' target='_blank' style='background-color: #25D366; color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; font-weight: bold;'>
                                    📲 Enviar Boas-Vindas
                                </a>
                             </div>";
    }

    public function listSelect(): array
    {
        $list = new \App\adms\Models\helper\AdmsRead();
        
        $list->fullRead("SELECT id id_cat, nome nome_cat FROM adms_categorias ORDER BY nome ASC");
        $registry['categorias'] = $list->getResult();

        if ($_SESSION['adms_access_level_id'] > 2){
            if (($_SESSION['adms_access_level_id'] == 4) or ($_SESSION['adms_access_level_id'] == 12)) {
                $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sits_users");
                $registry['sit_user'] = $list->getResult();

                $list->fullRead("SELECT clie.id, clie.razao_social, clie.nome_fantasia FROM adms_clientes as clie WHERE clie.empresa = :empresa order by clie.razao_social", "empresa={$_SESSION['emp_user']}");
                $registry['clie_user'] = $list->getResult();
            
                $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_clientes as emp WHERE empresa= :empresa", "empresa={$_SESSION['emp_user']}");
                $registry['emp'] = $list->getResult();

                $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
                $registry['lev'] = $list->getResult();
            }
        } else {
            $list->fullRead("SELECT id id_emp, nome_fantasia nome_fantasia_emp FROM adms_emp_principal ORDER BY nome_fantasia ASC");
            $registry['emp'] = $list->getResult();

            $list->fullRead("SELECT id as id_sit, name as name_sit FROM adms_sits_users");
            $registry['sit_user'] = $list->getResult();

            $list->fullRead("SELECT id id_lev, name name_lev FROM adms_access_levels WHERE order_levels >:order_levels ORDER BY name ASC", "order_levels=" . $_SESSION['order_levels']);
            $registry['lev'] = $list->getResult();
        }

        return ['emp' => $registry['emp'], 'sit_user' => $registry['sit_user'], 'lev' => $registry['lev'], 'clie_user' => $registry['clie_user'] ?? [], 'categorias' => $registry['categorias']];
    }   
}