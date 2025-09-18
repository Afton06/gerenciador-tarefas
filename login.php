<?php
session_start();
include 'conexao.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $pdo = Conexao::getConexao();
    $stmt = $pdo->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        // login OK
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario'] = $user['nome'];
        header("Location: index.php");
        exit();
    } else {
        $erro = "Email ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Login - Gerenciador</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if (!empty($erro)) echo "<p class='error'>" . htmlspecialchars($erro) . "</p>"; ?>
    <form method="POST">
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
    <p>Ainda não tem conta? <a href="register.php">Registre-se</a></p>
</div>
</body>
</html>