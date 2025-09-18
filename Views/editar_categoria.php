<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../Models/Categoria.php';
$categoria = Categoria::getById($_GET['id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Editar Categoria</h1>
    <form action="../Controllers/CategoriaController.php?action=editar" method="POST">
        <input type="hidden" name="id" value="<?= $categoria['id']; ?>">
        <input type="text" name="nome" value="<?= htmlspecialchars($categoria['nome']); ?>" required>
        <button type="submit">Salvar</button>
    </form>
    <a href="categorias.php">Cancelar</a>
</div>
</body>
</html>