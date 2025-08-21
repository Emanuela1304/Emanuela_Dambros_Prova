<?php
session_start();
require_once("conexao.php");

//VERIFICA SE O USUARIO TEM PERMISSAO DE adm OU secretaria]
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
    echo "<script>alert('Acesso negado!');window.location.href='principal.php'</script>";
    exit();
}

$produtos = []; //INICIALIZA A VARIAVEL PARA EVITAR ERROS

//SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU NOME
if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['busca_produto'])) {
    $busca = trim($_POST['busca_produto']);

    //VERIFICA SE A BUSCA É UM NUMERO OU NOME
    if (is_numeric($busca)) {
        $sql = "SELECT * FROM produto WHERE id_produto = :busca ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM produto WHERE produto WHERE nome_prod LIKE :busca_nome ORDER BY nome_prod ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
    }

} else {
    $sql = "SELECT * FROM produto ORDER BY nome_prod ASC";
    $stmt = $pdo->prepare($sql);
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
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Produtos</title>
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

    

    <form action="buscar_produto.php" method="POST">
        <label for="busca">Digite o ID ou NOME(opcional): </label>
        <input type="text" id="busca" name="busca">
        <button type="submit">Confirmar</button>
    </form>

    <?php if(!empty($usuarios)): ?>

        <table class="table">
            <tr>
                <th>Nome Produto</th>
                <th>Descricao</th>
                <th>Quantidade</th>
                <th>Valor Unitario</th>
            </tr>

        <?php foreach($produtos as $produto): ?>

            <tr>
                <td><?=htmlspecialchars($produto['id_produto'])?> </td>
                <td><?=htmlspecialchars($produto['nome_prod'])?> </td>
                <td><?=htmlspecialchars($produto['descricao'])?> </td>
                <td><?=htmlspecialchars($produto['qtde'])?> </td>
                <td><?=htmlspecialchars($produto['valor_unit'])?> </td>
                <td>
                    <a href="alterar_produto.php?id=htmlspecialchars($produto['id_produto'])?>">Alterar</a>

                    <a href="excluir_produto.php?id=htmlspecialchars($produto['id_produto'])?>"onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
        </td>
            </tr>
        <?php endforeach;?>
        </table>
        <?php else: ?>
            <p>Nenhum produto  encontrado.</p>
            <?php endif; ?>
            <a href="principal.php">Voltar</a>
</body>
</html>