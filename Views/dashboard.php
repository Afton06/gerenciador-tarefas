<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']); ?>!</h1>
    <nav>
        <a href="tarefas.php">Tarefas</a> |
        <a href="categorias.php">Categorias</a> |
        <a href="../Controllers/UsuarioController.php?action=logout">Sair</a>
    </nav>
    <hr>
    <p>Use o menu acima para gerenciar suas tarefas e categorias.</p>
</div>
</body>
</html>