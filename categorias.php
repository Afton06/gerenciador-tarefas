<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();

// Adicionar categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])) {
    $nome = $_POST['nome'];
    $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
    $stmt->execute([$nome]);
    header("Location: categorias.php");
    exit();
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: categorias.php");
    exit();
}

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Categorias</h1>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nova categoria" required>
        <button type="submit">Adicionar</button>
    </form>
    <h2>Lista</h2>
    <ul>
        <?php foreach ($categorias as $cat): ?>
            <li>
                <?= htmlspecialchars($cat['nome']); ?> 
                <a href="categorias.php?excluir=<?= $cat['id']; ?>" onclick="return confirm('Excluir categoria?')">Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <br>
    <a href="index.php">Voltar</a>
</div>
</body>
</html>