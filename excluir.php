<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara o comando SQL para exclusão
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Redireciona para a página inicial
        exit();
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>