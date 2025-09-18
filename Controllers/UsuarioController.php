<?php
session_start();
require_once '../Models/Conexao.php';

class UsuarioController {
    public static function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $pdo = Conexao::getConexao();
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=?");
            $stmt->execute([$email, $senha]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario'] = $usuario['nome'];
                header("Location: ../Views/dashboard.php");
                exit();
            } else {
                $_SESSION['error_message'] = "Email ou senha incorretos!";
                header("Location: ../Views/login.php");
                exit();
            }
        }
    }

    public static function logout() {
        session_destroy();
        header("Location: ../Views/login.php");
        exit();
    }
}

// Rotas simples
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'login') UsuarioController::login();
    if ($_GET['action'] === 'logout') UsuarioController::logout();
}