<?php
session_start();
include_once 'Tarefa.php';

$tarefa = new Tarefa();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'] ?: null;
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;

    if ($tarefa->adicionar($titulo, $descricao, $id_categoria, $data_vencimento)) {
        $_SESSION['message'] = "Tarefa adicionada com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao adicionar tarefa.";
    }

    header("Location: index.php");
    exit();
}
?>