<?php
session_start();
require_once("conexao.php");

//VERIFICA SE O USUARIO TEM PERMISSAO
//SUPONDO QUE O PERFIL 1 SEJA O ADMINISTRADOR

if ($_SESSION['perfil'] != 1) {
    echo "Acesso negado!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);
    $id_perfil = $_POST["id_perfil"];

    $sql = "INSERT INTO usuario (nome, email, senha, id_perfil) VALUES (:nome,:email,:senha,:id_perfil)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('nome', $nome, );
    $stmt->bindParam('email', $email, );
    $stmt->bindParam('senha', $senha, );
    $stmt->bindParam('id_perfil', $id_perfil, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário cadastrado com sucesso!')</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar usuário!')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar usuário</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h2>Cadastrar Usuário</h2>
    <form action="cadastro_usuario.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome">

        <label for="email">Email:</label>
        <input type="email" name="email" id="email">

        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha">

        <label for="id_perfil">Perfil:</label>
        <select name="id_perfil" id="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>

            <button type="submit"> Salvar </button>
            <button type="reset"> Cancelar </button>
    </form>

    <a href="principal.php">Voltar</a>

    <address>Trabalho desenvolvido pelo aluno Pedro Gabriel | Técnico Dev Sistemas</address>
</body>

</html>