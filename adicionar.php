<?php
session_start();
include 'conexao.php';
$pdo = Conexao::getConexao();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;

    // Limite de 20 caracteres na descrição
    if (strlen($descricao) > 20) {
        $_SESSION['error_message'] = "A descrição deve ter no máximo 20 caracteres!";
        $_SESSION['form_data'] = [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'id_categoria' => $id_categoria,
            'data_vencimento' => $data_vencimento
        ];
        header("Location: index.php");
        exit();
    }

    $data_criacao = date('Y-m-d H:i:s');
    $stmt = $pdo->prepare("
        INSERT INTO tarefas (titulo, descricao, id_categoria, data_criacao, data_vencimento, id_usuario) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$titulo, $descricao, $id_categoria, $data_criacao, $data_vencimento, $id_usuario]);

    $_SESSION['message'] = "Tarefa adicionada com sucesso!";
    header("Location: index.php");
    exit();
}
?>
