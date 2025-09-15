<?php
session_start();
include 'conexao.php';

// Cria uma instância da classe de conexão
$database = new Conexao();

// Obtém a conexão ativa
$conn = $database->getConnection();

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];

    // Define a data de criação manualmente no PHP
    $data_criacao = date('Y-m-d H:i:s');
    
    // Prepara o comando SQL para inserção segura
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria, data_criacao) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $titulo, $descricao, $id_categoria, $data_criacao);

    // Executa o comando
    if ($stmt->execute()) {
        $_SESSION['message'] = "Tarefa adicionada com sucesso!";
        header("Location: index.php"); // Redireciona para a página principal
        exit();
    } else {
        $_SESSION['error_message'] = "Erro ao adicionar tarefa: " . $stmt->error;
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}

// Fecha a conexão com o banco de dados
$database->closeConnection();
?>