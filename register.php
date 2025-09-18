<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Conexao::getConexao();
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $_SESSION['error_message'] = "E-mail já cadastrado!";
        header("Location: register.php");
        exit;
    }

    $hash = password_hash($senha, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $hash]);

    $_SESSION['message'] = "Cadastro realizado com sucesso! Faça login.";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Criar Conta</h1>
    <?php if (!empty($_SESSION['error_message'])): ?>
        <p class="error"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br>
        <button type="submit">Registrar</button>
    </form>
    <p>Já tem conta? <a href="login.php">Faça login</a></p>
</div>
</body>
</html>
