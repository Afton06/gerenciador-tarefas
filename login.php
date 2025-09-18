<?php
session_start();
include 'conexao.php';
$pdo = Conexao::getConexao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario'] = $user['nome'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Email ou senha incorretos.";
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

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Senha:</label>
        <div class="password-field">
            <input type="password" name="senha" id="senha" required>
            <span class="toggle-password" onclick="toggleSenha()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.333-8-5.333S0 8 0 8s3 5.333 8 5.333S16 8 16 8zM8 11.333A3.333 3.333 0 1 1 8 4.667a3.333 3.333 0 0 1 0 6.666z"/>
                    <path d="M8 6.667a1.333 1.333 0 1 0 0 2.666A1.333 1.333 0 0 0 8 6.667z"/>
                </svg>
            </span>
        </div>

        <button type="submit">Entrar</button>

        <div class="links">
            <a href="forgot.php">Esqueci minha senha</a>
            <a href="register.php">Criar conta</a>
        </div>
    </form>
</div>

<script>
function toggleSenha() {
    var input = document.getElementById('senha');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>