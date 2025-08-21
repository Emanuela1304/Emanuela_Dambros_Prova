<?php
session_start();
require 'conexao.php';

if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

if($_SERVER["REQUEST_METHOD"] =="POST"){
    $id_produto = $_POST['id_produto'];
    $nome_prod = $_POST['nome_prod'];
    $descricao = $_POST['descricao'];
    $qtde = $_POST['qtde'];
    $valor_unit = $_POST['valor_unit'];

    //atualiza os dados do usuario
    
    
        $sql = "UPDATE produto SET nome_prod = :nome_prod,descricao = :descricao,qtde=:qtde WHERE id_produto = :id_produto";
        $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':nome_prod',$nome_prod);
    $stmt->bindParam(':descricao',$descricao);
    $stmt->bindParam(':qtde',$id_perfil);
    $stmt->bindParam(':id_produto',$id_produto);
    
    if($stmt->execute()){
        echo "<script>alert('Produto atualizado co sucesso!');window.location.href='buscar_usuario.php';</script>";

    }else{
        echo "<script>alert('Erro ao atualizar produto');window.location.href=alterar_produto.php?id=$id_produto';</script>";
    }
}
?>