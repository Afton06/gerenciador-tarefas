<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if (isset($_SESSION['erro_login'])): ?>
        <p class="error"><?php echo $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?></p>
    <?php endif; ?>
    <form method="POST" action="processa_login.php">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>