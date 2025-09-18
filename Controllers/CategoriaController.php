<?php
session_start();
require_once '../Models/Categoria.php';

class CategoriaController {
    public static function listar() {
        $categorias = Categoria::getAll();
        include '../Views/categorias.php';
    }

    public static function adicionar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            Categoria::add($nome);
            header("Location: ../Views/categorias.php");
            exit();
        }
    }

    public static function editar() {
        $id = $_GET['id'] ?? $_POST['id'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = $_POST['nome'];
            Categoria::update($id, $nome);
            header("Location: ../Views/categorias.php");
            exit();
        } else {
            $categoria = Categoria::getById($id);
            include '../Views/editar_categoria.php';
        }
    }

    public static function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Categoria::delete($id);
        }
        header("Location: ../Views/categorias.php");
        exit();
    }
}

// Rotas
$action = $_GET['action'] ?? null;
if ($action) {
    if ($action === 'adicionar') CategoriaController::adicionar();
    if ($action === 'editar') CategoriaController::editar();
    if ($action === 'excluir') CategoriaController::excluir();
    if ($action === 'listar') CategoriaController::listar();
}