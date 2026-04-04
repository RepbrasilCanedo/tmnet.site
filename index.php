<?php

session_start();
ob_start();

define('D0O8C0A3N1E9D6O1', true);

//Carregar o Composer
require './vendor/autoload.php';

//Instanciar a classe ConfigController, responsável em tratar a URL
$home = new Core\ConfigController();

//Instanciar o método para carregar a página/controller
$home->loadPage();
