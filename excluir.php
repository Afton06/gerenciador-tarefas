<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();
$id_usuario = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    // verificar propriedade
    $stmt = $pdo->prepare("SELECT id_usuario FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        die("Tarefa não encontrada.");
    }
    if ($row['id_usuario'] != $id_usuario) {
        die("Acesso negado.");
    }

    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: index.php");
exit();