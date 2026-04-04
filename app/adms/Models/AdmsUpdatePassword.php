<?php
namespace App\adms\Models;

if (!defined('D0O8C0A3N1E9D6O1')) { header("Location: /"); die(); }

class AdmsUpdatePassword
{
    private bool $result = false;
    private array|null $resultBd;

    function getResult(): bool { return $this->result; }

    // Valida se a chave existe no banco
    public function valKey(?string $key): bool
    {
        if (empty($key)) return false;

        $read = new \App\adms\Models\helper\AdmsRead();
        // Verifica a chave E se a solicitação não é muito antiga (Opcional, mas recomendado)
        $read->fullRead("SELECT id FROM adms_users WHERE recover_password = :key LIMIT 1", "key={$key}");
        $this->resultBd = $read->getResult();

        if ($this->resultBd) {
            return true;
        }
        return false;
    }

    public function update(array $data): void
    {
        if ($this->valKey($data['key'])) {
            // Criptografa a nova senha
            $newPassword = password_hash($data['password'], PASSWORD_DEFAULT);

            $update = new \App\adms\Models\helper\AdmsUpdate();
            // Atualiza a senha e LIMPA o campo recover_password para o link não ser usado 2 vezes
            $update->exeUpdate("adms_users", [
                'password' => $newPassword,
                'recover_password' => null, 
                'date_recover' => null,
                'modified' => date("Y-m-d H:i:s")
            ], "WHERE id=:id", "id={$this->resultBd[0]['id']}");

            if ($update->getResult()) {
                $this->result = true;
            } else {
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }
}