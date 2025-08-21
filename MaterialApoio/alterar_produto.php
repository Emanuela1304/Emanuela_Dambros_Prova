<?php
session_start();
require_once("conexao.php");

//Verifica se o usuario tem permissao de adm
if ($_SESSION['perfil'] !=1){ 
echo "<script>alert('Acesso negado!');window.location.href='principal.php';</script>";
exit();
}

//inicializa variaveis
$produto = null;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!empty($_POST['busca_produto'])){
        $busca = trim($_POST['busca_produto']);

        //verifica se a busca e um numero (id) ou um nome
        if(is_numeric($busca)){
            $sql = "SELECT * FROM produto WHERE id_produto = :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca',$busca,PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM produto WHERE nome_prod LIKE :busca_nome";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome',"$busca%",PDO::PARAM_STR);
        }

        $stmt->execute();
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);

        //Se o produto nao for encontrado, exibe um alerta
        if(!$produto){
            echo "<script>alert('Produto nao encontrado');</script>";
        }
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
    <title>Alterar Produto</title>
    <link rel="stylesheet" href="styles.css">
    <!-- certifique-se que o javascript esta sendo carregado corretamente -->
    <script src="scripts.js"></script>
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
    <h2>Alterar Produto</h2>

    <form action="alterar_produto.php" method="POST">
        <label for="busca_produto">Digite o nome do produto</label>
        <input type="text" id="busca_produto" name="busca_produto" required onkeyup="buscarSugestoes()">
        
        <!-- div para exibir sugestoes de usuarios -->
        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
</form>

<?php if($produto):?>
    <!-- formulario para alterar produto -->
    <form action="processa_alteracao_produto.php" method="POST">
        <input type="hidden" name="id_produto" value="<?=htmlspecialchars($produto['id_produto'])?>">

        <label for="nome_prod">Nome Produto:</label>
        <input type="text"  id="nome_prod"name="nome_prod" value="<?=htmlspecialchars($produto['nome_prod'])?>"required>

        <label for="descricao">Descrição:</label>
        <input type="text"  id="descricao" name="descricao" value="<?=htmlspecialchars($produto['descricao'])?>"required>

        <label for="qtde">Quantidade:</label>
        <input type="number"  id="qtde" name="qtde" value="<?=htmlspecialchars($produto['qtde'])?>"required>

        <label for="valor_unit">Valor_Unitario:</label>
        <input type="number"  id="valor_unit" name="valor_unit" value="<?=htmlspecialchars($produto['valor_unit'])?>"required>


       
        
        <button type="submit">Alterar</button>
        <button type="reset">Cancelar</button>
    </form>
    <?php endif; ?>
    <a href="principal.php">Voltar</a>
</body>
</html>