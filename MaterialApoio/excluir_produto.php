<?php
session_start();
require "conexao.php";

//verifica se o usuario tem permissao de adm
if($_SESSION['perfil'] !=1){
    echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
    exit();
}

//inicializa variavel para armazenar usuarios
$produtos = [];

//busca todos os usuarios cadastrados em ordem alfabetica
$sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// se um id for passado via get exclui o usuario
if(isset($_GET['id']) && is_numeric($_GET['id'])){
    $id_produto = $_GET['id'];

    //exclui o usuario do banco de dados
    $sql = "DELETE FROM produto WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id',$id_produto,PDO::PARAM_INT);

    if($stmt->execute()){
        echo "<script>alert('Produto excluido com sucesso!');window.location.href='excluir_usuario.php';</script>";
    }else{
        echo "<script>alert('Nao foi possivel excluir o produto!');</script>";
    }
}
$id_perfil = $_SESSION['perfil'];
$permissoes = [
    //adm
    1 => [
        "Cadastrar" => [
            "cadastro_usuario.php",
            "cadastro_perfil.php",
            "cadastro_cliente.php",
            "cadastro_fornecedor.php",
            "cadastro_produto.php",
            "cadastro_funcionario.php"
        ],
        "Buscar" => [
            "buscar_usuario.php",
            "buscar_perfil.php",
            "buscar_cliente.php",
            "buscar_fornecedor.php",
            "buscar_produto.php",
            "buscar_funcionario.php"
        ],
        "Alterar" => [
            "alterar_usuario.php",
            "alterar_perfil.php",
            "alterar_cliente.php",
            "alterar_fornecedor.php",
            "alterar_produto.php",
            "alterar_funcionario.php"
        ],
        "Excluir" => [
            "excluir_usuario.php",
            "excluir_perfil.php",
            "excluir_cliente.php",
            "excluir_fornecedor.php",
            "excluir_produto.php",
            "excluir_funcionario.php"
        ]
    ],
    //secretaria
    2 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php","buscar_usuario.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php"]
    ],
    //almoxarife
    3 => [
        "Cadastrar" => ["cadastro_fornecedor.php", "cadastro_produto.php"],
        "Buscar" => ["buscar_cliente.php", "buscar_fornecedor.php", "buscar_produto.php"],
        "Alterar" => ["alterar_fornecedor.php", "alterar_produto.php"],
        "Excluir" => ["excluir_produto.php",]
    ],
    //cliente
    4 => [
        "Cadastrar" => ["cadastro_cliente.php"],
        "Buscar" => ["buscar_produto.php"],
        "Alterar" => ["alterar_cliente.php"],
    ],
];

//OBTENDO AS OPCOES DISPONIVEIS PARA O PERFIL LOGADO
$opcoes_menu = $permissoes[$id_perfil];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Produto</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    
</head>
<body>
<nav>
        <ul class="menu">
            <?php foreach ($opcoes_menu as $categoria => $arquivos): ?>
                <li class="dropdown">
                    <a href="#"><?= $categoria ?></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($arquivos as $arquivo): ?>
                            <li>
                                <a href="<?= $arquivo ?>"><?= ucfirst(str_replace("_", " ", basename($arquivo, ".php"))) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <h2>Excluir Produto</h2>
    <?php if(!empty($produtos)): ?>
        <table border="1">
        <tr>
            <td>Nome Produto:</td>
            <td>Descrição:</td>
            <td>Quantidade:</td>
            <td>Valor Unitario:</td>
            
    </tr>
    <?php foreach($produtos as $produto): ?>
        <tr> 
            <td><?= htmlspecialchars($produto['id_produto'])?></td>
            <td><?= htmlspecialchars($produto['nome_prod'])?></td>
            <td><?= htmlspecialchars($produto['descricao'])?></td>
            <td><?= htmlspecialchars($produto['qtde'])?></td>
            <td><?= htmlspecialchars($produto['valor_unit'])?></td>
            <td> 
                <a href="excluir_produto.php?id=<?= htmlspecialchars($produto['id_produto'])?>"onclick="return confirm('tem certeza que deseja excluir produto?')">Excluir</a>
    </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else:?>
        <p>Nenhum produto encontrado</p>
        <?php endif; ?>
        <a href="principal.php">Voltar</a>
</body>
</html>