<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../Models/Categoria.php';
$categorias = Categoria::getAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Categorias</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Categorias</h1>
    <a href="dashboard.php">Voltar</a> | 
    <a href="../Controllers/UsuarioController.php?action=logout">Sair</a>

    <h2>Adicionar Categoria</h2>
    <form action="../Controllers/CategoriaController.php?action=adicionar" method="POST">
        <input type="text" name="nome" required>
        <button type="submit">Adicionar</button>
    </form>

    <h2>Lista de Categorias</h2>
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($categorias): ?>
            <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['nome']); ?></td>
                    <td>
                        <a href="editar_categoria.php?id=<?= $c['id']; ?>">Editar</a>
                        <a href="../Controllers/CategoriaController.php?action=excluir&id=<?= $c['id']; ?>" onclick="return confirm('Excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2">Nenhuma categoria encontrada.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>