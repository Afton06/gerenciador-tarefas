<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;

    $sql = "INSERT INTO tarefas (titulo, descricao, data_vencimento, id_categoria) 
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $data_vencimento, $id_categoria]);

    header("Location: index.php");
    exit();
}