<?php

namespace Core;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Configurações básicas do site.
 *
  * @author Daniel Canedo - docan2006@gmail.com
 */

abstract class Config
{
    /**
     * Possui as constantes com as configurações.
     * Configurações de endereço do projeto.
     * Página principal do projeto.
     * Credenciais de acesso ao banco de dados
     * E-mail do administrador.
     * 
     * @return void
     *'"
     */
    protected function configAdm(): void
    {
        define('URL', 'http://localhost/tmnet.site/');
        define('URLADM', 'http://localhost/tmnet.site/');        
        define('URLSTS', 'http://localhost/tmnet.site/');

        define('CONTROLLER', 'Login');
        define('METODO', 'index');
        define('CONTROLLERERRO', 'Login');

        // Configurações de E-mail
        define('EMAIL_HOST', 'smtp.titan.email');
        define('EMAIL_USER', 'atendimento@repbrasil.salvador.br');
        define('EMAIL_PASS', 'REPbr@s#l9624'); // A senha fica aqui, centralizada
        define('EMAIL_PORT', 465);
        define('EMAIL_FROM_NAME', 'Tmnet Sistema');


        define('HOST', 'localhost');
        define('USER', 'root');
        define('PASS', '');
        define('DBNAME', 'tmnet_site');
        define('PORT', 3306);

        define('EMAILADM', 'docan2006@gmail.com');
    }
}
