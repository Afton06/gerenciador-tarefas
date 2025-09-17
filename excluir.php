<?php
session_start();
include_once 'Tarefa.php';

$tarefa = new Tarefa();

if (isset($_GET['id'])) {
    if ($tarefa->excluir($_GET['id'])) {
        $_SESSION['message'] = "Tarefa excluída com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao excluir tarefa.";
    }
}
header("Location: index.php");
exit();
?>