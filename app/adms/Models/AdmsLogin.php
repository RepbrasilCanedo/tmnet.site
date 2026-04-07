<?php
namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) {
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class AdmsLogin {
    private array|null $data;
    private array|null $resultBd;
    private bool $result = false;

    function getResult(): bool { return $this->result; }

    public function login(array $data): void {
        $this->data = $data;
        
        // =========================================================================
        // DOCAN FIX: Blindagem pesada contra o Autofill e caracteres especiais!
        // =========================================================================
        // 1. Remove espaços invisíveis que o navegador/telemóvel coloca no fim
        // 2. Força para minúsculas logo no servidor
        $userClean = trim(strtolower($this->data['user']));
        
        // 3. O urlencode() é a chave de ouro! Evita que símbolos especiais 
        // quebrem o parse_string da sua classe AdmsRead.
        $userBind = urlencode($userClean);

        $readUser = new \App\adms\Models\helper\AdmsRead();

        // Tenta buscar no Administrativo (adms_users)
        $readUser->fullRead("SELECT usr.*, lev.order_levels, emp.base_dados 
                FROM adms_users AS usr
                INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                INNER JOIN adms_emp_principal AS emp ON emp.id=usr.empresa_id
                WHERE (usr.adms_sits_user_id= :situacao) AND (usr.user =:user OR usr.email =:email) 
                LIMIT :limit", "situacao=1&user={$userBind}&email={$userBind}&limit=1");

        $this->resultBd = $readUser->getResult();

        // Se não encontrar, tenta no Usuário Final (adms_users_final)
        if (!$this->resultBd) {
            $readUser->fullRead("SELECT usr.*, lev.order_levels 
                    FROM adms_users_final AS usr
                    INNER JOIN adms_access_levels AS lev ON lev.id=usr.adms_access_level_id
                    WHERE (usr.adms_sits_user_id= :situacao) AND (usr.user=:user OR usr.email =:email) 
                    LIMIT :limit", "situacao=1&user={$userBind}&email={$userBind}&limit=1");
            $this->resultBd = $readUser->getResult();
        }

        if ($this->resultBd) {
            $this->valPassword();
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Usuário não encontrado!</div>";
            $this->result = false;
        }
    }

    private function valPassword(): void {
        if (password_verify($this->data['password'], $this->resultBd[0]['password'])) {
            $res = $this->resultBd[0];

            $_SESSION['user_id'] = $res['id'];
            $_SESSION['user_name'] = $res['name'];
            $_SESSION['user_nickname'] = $res['apelido'];
            $_SESSION['user_email'] = $res['email'];
            $_SESSION['user_image'] = $res['imagem'];
            $_SESSION['adms_access_level_id'] = $res['adms_access_level_id'];
            $_SESSION['order_levels'] = $res['order_levels'];
            $_SESSION['emp_user'] = $res['empresa_id'] ?? null;
            $_SESSION['set_clie'] = $res['cliente_id'] ?? null;
            $_SESSION['base_dados'] = $res['base_dados'] ?? null;
            
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<div class='alert-danger'>Erro: Senha incorreta!</div>";
            $this->result = false;
        }
    }
}