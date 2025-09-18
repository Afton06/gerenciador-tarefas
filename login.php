<?php
session_start();
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = Conexao::getConexao();
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error_message'] = "E-mail ou senha inválidos.";
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if (!empty($_SESSION['error_message'])): ?>
        <p class="error"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Email:</label><br>
        <input type="text" name="email" required><br>
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br>
        <button type="submit">Entrar</button>
    </form>
    <p>Não tem conta? <a href="register.php">Cadastre-se</a></p>
</div>
</body>
</html>