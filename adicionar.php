<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?: null;
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;
    $id_usuario = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, data_vencimento, id_categoria, id_usuario) VALUES (?,?,?,?,?)");
    $stmt->execute([$titulo, $descricao, $data_vencimento, $id_categoria, $id_usuario]);

    header("Location: index.php");
    exit();
}
