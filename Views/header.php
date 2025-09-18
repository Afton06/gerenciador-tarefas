<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>Gerenciador de Tarefas</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a> |
            <a href="tarefas.php">Tarefas</a> |
            <a href="categorias.php">Categorias</a> |
            <a href="logout.php">Logout</a>
        </nav>
        <hr>
    </header>