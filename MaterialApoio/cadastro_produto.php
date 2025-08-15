<?php
session_start();
require_once("conexao.php");

// VERIFICA SE O USUARIO TEM PERMISSAO
// SUPONDO QUE O PERFIL 1 SEJA O ADMINISTRADOR
if ($_SESSION['perfil'] != 1) {
    echo "Acesso negado!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_prod = $_POST["nome_prod"];
    $descricao = $_POST["descricao"];
    $qtde = $_POST["qtde"];
    $valor_unit = $_POST["valor_unit"];

    $sql = "INSERT INTO produto (nome_prod, descricao, qtde, valor_unit) VALUES (:nome_prod, :descricao, :qtde, :valor_unit)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome_prod', $nome_prod);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':qtde', $qtde, PDO::PARAM_INT);
    $stmt->bindParam(':valor_unit', $valor_unit);

    if ($stmt->execute()) {
        echo "<script>alert('Produto cadastrado com sucesso!');</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar produto!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <h2>Cadastrar Produto</h2>
    <form action="cadastro_produto.php" method="POST">
        <label for="nome_prod">Nome do Produto:</label>
        <input type="text" name="nome_prod" id="nome_prod" required>

        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" id="descricao" required>

        <label for="qtde">Quantidade:</label>
        <input type="number" name="qtde" id="qtde" required min="0">

        <label for="valor_unit">Valor Unitário:</label>
        <input type="number" step="0.01" name="valor_unit" id="valor_unit" required min="0">

        <button type="submit"> Salvar </button>
        <button type="reset"> Cancelar </button>
    </form>

    <a href="principal.php">Voltar</a>

</body>

</html>
