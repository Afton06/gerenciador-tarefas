<?php
session_start();
require_once '../Models/Usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $usuario = Usuario::autenticar($email, $senha);
    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        header("Location: dashboard.php");
        exit();
    } else {
        $erro = "Email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <?php if(isset($erro)) echo "<p class='error'>".$erro."</p>"; ?>
    <form action="" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>
        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>