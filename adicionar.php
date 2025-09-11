<?php
// Inclui o arquivo de conexão
include 'conexao.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];

    // Prepara o comando SQL para inserção segura
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $titulo, $descricao, $id_categoria);

    // Executa o comando
    if ($stmt->execute()) {
        header("Location: index.php"); // Redireciona para a página principal
        exit();
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>