<?php
session_start();
include 'conexao.php';
$pdo = Conexao::getConexao();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("UPDATE usuarios SET reset_token = ? WHERE email = ?");
        $stmt->execute([$token, $email]);

        $link = "http://localhost/gerenciador_tarefas/reset.php?token=$token";
        $assunto = "Recuperação de senha";
        $mensagem_email = "Clique no link para resetar sua senha: $link";
        $headers = "From: no-reply@teste.com";

        if (mail($email, $assunto, $mensagem_email, $headers)) {
            $message = "Um email de recuperação foi enviado!";
        } else {
            $message = "Erro ao enviar email. Configure o servidor de envio ou use PHPMailer.";
        }
    } else {
        $message = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Esqueci a Senha</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Esqueci a Senha</h1>

    <?php if ($message) echo "<div class='message'>$message</div>"; ?>

    <form method="POST">
        <label>Email cadastrado:</label>
        <input type="email" name="email" required>
        <button type="submit">Enviar link de recuperação</button>
    </form>
</div>
</body>
</html>