<?php
session_start();
require_once 'conexao.php';

//garante que o usuario esteja logado
if (!isset($_SESSION['id_usuario'])){
    echo "<script>alert('Acesso Negado!');window.location.href='login.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id_usuario = $_SESSION['id_usuario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirma_senha'];
     
    if($nova_senha !== $confirmar_senha){
        echo "<script>alert('As senhas nao coincidem');</script>";

    }elseif(strlen($nova_senha)<8){
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";

    }elseif($nova_senha == "temp123"){
        echo "<script>alert('Escolha uma senha diferente de temporaria');</script>";
}else{
    $senha_hash = password_hsh($nova_senha,PASSWORD_DEFAULT);

    // Atualiza a senha e remove o status de temporaria 
    $sql"UPDATE usuario SET senha= :senha, senha_temporaria = FALSE WHERE id_usuario = :id";
    $stmt= $pdo->prepare($sql);
        $stmt->bindParam(':senha',$senha_hash);
        $stmt->bindParam(':id',$id_usuario);

}

?>