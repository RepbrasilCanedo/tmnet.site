<?php

namespace App\adms\Controllers;

if(!defined('D0O8C0A3N1E9D6O1')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller inserir anexos
 * @author Daniel Canedo - docan2006@gmail.com
 */

//Verificar se o usuário clicou no botão, clicou no botão acessa o IF e tenta cadastrar, caso contrario acessa o ELSE
$SendAneArq = filter_input(INPUT_POST, 'SendAneArq', FILTER_SANITIZE_STRING);
if ($SendAneArq) {
    //Receber os dados do formulário
    $nome_arq = $_SESSION['num_cham'];
    //$nome_arq = filter_input(INPUT_POST, 'nome_arq', FILTER_SANITIZE_STRING);
    $nome_imagem = $_FILES['imagem_arq']['name'];

    //Inserir no BD
    $result_img = "INSERT INTO arquivos (nome, arquivo) VALUES (:nome, :arquivo)";
    $insert_msg = $conexao->prepare($result_img);
    $insert_msg->bindParam(':nome', $nome_arq);
    $insert_msg->bindParam(':arquivo', $nome_imagem);

    //Verificar se os dados foram inseridos com sucesso
    if ($insert_msg->execute()) {
        //Recuperar último ID inserido no banco de dados
        $ultimo_id = $_SESSION['num_cham'];
       // $ultimo_id = $conexao->lastInsertId();

        //Diretório onde o arquivo vai ser salvo
        $diretorio =  URLADM . 'app/adms/assets/arquivos/historicos/' . $ultimo_id.'/';
        //Criar a pasta de foto 
        mkdir($diretorio, 0755);
        
        if(move_uploaded_file($_FILES['imagem_arq']['tmp_name'], $diretorio.$nome_imagem)){
            $_SESSION['msg'] = "<p style='color:green;'>Dados salvo com sucesso e upload realizado com sucesso</p>";
            header("Location: cad_apontamentos.php");
        }else{
            $_SESSION['msg'] = "<p><span style='color:green;'>Dados salvo com sucesso. </span><span style='color:red;'>Erro ao realizar o upload da imagem</span></p>";
            header("Location: cad_apontamentos.php");
        }        
    } else {
        $_SESSION['msg'] = "<p style='color:red;'>Erro ao salvar os dados</p>";
        header("Location: cad_apontamentos.php");
    }
} else {
    $_SESSION['msg'] = "<p style='color:red;'>Erro ao salvar os dados</p>";
    header("Location: cad_apontamentos.php");
}