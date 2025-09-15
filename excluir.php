<?php
session_start();
include 'conexao.php';

// Cria uma instância da classe de conexão
$database = new Conexao();

// Obtém a conexão ativa
$conn = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Tarefa excluída com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erro ao excluir: " . $stmt->error;
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}

// Fecha a conexão com o banco de dados
$database->closeConnection();
?>