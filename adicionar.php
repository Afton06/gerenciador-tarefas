<?php
// Inicia a sessão para usar mensagens de feedback
session_start();

// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validação de Dados
    if (empty(trim($_POST['titulo']))) {
        $_SESSION['error_message'] = "O título da tarefa não pode estar vazio.";
        header("Location: index.php");
        exit();
    }
    
    $titulo = trim($_POST['titulo']);
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    
    // 2. Tratamento para categoria vazia
    if (empty($id_categoria)) {
        $id_categoria = NULL;
    }

    // Prepara o comando SQL para inserção
    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria) VALUES (?, ?, ?)");
    // A letra 's' representa string, 'i' representa integer (int)
    $stmt->bind_param("ssi", $titulo, $descricao, $id_categoria);

    // Executa o comando e verifica o sucesso
    if ($stmt->execute()) {
        // 3. Feedback visual: define uma mensagem de sucesso na sessão
        $_SESSION['message'] = "Tarefa adicionada com sucesso!";
        header("Location: index.php"); // Redireciona
        exit();
    } else {
        $_SESSION['error_message'] = "Erro ao adicionar a tarefa: " . $stmt->error;
        header("Location: index.php"); // Redireciona em caso de erro
        exit();
    }

    $stmt->close();
    $conn->close();

} else {
    // Se o acesso for direto (sem POST), redireciona
    header("Location: index.php");
    exit();
}
?>