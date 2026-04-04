<?php

namespace Core;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

class ConfigView
{
    public function __construct(private string $nameView, private array|string|null $data)
    {
    }

    public function loadView():void
    {
        if(file_exists('app/' .$this->nameView . '.php')){
            include 'app/adms/Views/include/head.php';
            include 'app/adms/Views/include/navbar.php';
            
            // --- INÍCIO DA CORREÇÃO ---
            // 1. Abre o container FLEX que coloca Menu e Conteúdo lado a lado
            echo "<div class='main-container'>";                 
                include 'app/adms/Views/include/menu.php';                
                // 2. Wrapper envolve o conteúdo da página para ele não cair
                echo "<div class='wrapper'>";
                    include 'app/' .$this->nameView . '.php';
                echo "</div>";                
            echo "</div>"; 
            // --- FIM DA CORREÇÃO ---

            include 'app/adms/Views/include/footer.php';
        }else{
            die("Erro - 002: Por favor tente novamente.");
        }
    }

    public function loadViewLogin():void
    {
        if(file_exists('app/' .$this->nameView . '.php')){
            include 'app/adms/Views/include/head_login.php';
            include 'app/' .$this->nameView . '.php';
            include 'app/adms/Views/include/footer_login.php';
        }else{
            die("Erro - 005: Por favor tente novamente.");
        }
    }
}