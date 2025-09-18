<?php
session_start();
require_once '../Models/Tarefa.php';
require_once '../Models/Categoria.php';

class TarefaController {
    public static function listar() {
        $status = $_GET['status'] ?? null;
        $tarefas = Tarefa::getAll($status);
        $categorias = Categoria::getAll();
        include '../Views/tarefas.php';
    }

    public static function adicionar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $id_categoria = $_POST['id_categoria'] ?: null;
            $data_vencimento = $_POST['data_vencimento'] ?: null;

            Tarefa::add($titulo, $descricao, $id_categoria, $data_vencimento);
            header("Location: ../Views/tarefas.php");
            exit();
        }
    }

    public static function editar() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $descricao = $_POST['descricao'];
            $status = $_POST['status'];
            $id_categoria = $_POST['id_categoria'] ?: null;
            $data_vencimento = $_POST['data_vencimento'] ?: null;

            Tarefa::update($id, $titulo, $descricao, $status, $id_categoria, $data_vencimento);
            header("Location: ../Views/tarefas.php");
            exit();
        } else {
            $tarefa = Tarefa::getById($id);
            $categorias = Categoria::getAll();
            include '../Views/editar_tarefa.php';
        }
    }

    public static function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Tarefa::delete($id);
        }
        header("Location: ../Views/tarefas.php");
        exit();
    }
}

// Rotas
$action = $_GET['action'] ?? null;
if ($action) {
    if ($action === 'adicionar') TarefaController::adicionar();
    if ($action === 'editar') TarefaController::editar();
    if ($action === 'excluir') TarefaController::excluir();
    if ($action === 'listar') TarefaController::listar();
}