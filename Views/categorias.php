<?php
require_once 'header.php';
require_once '../Controllers/CategoriaController.php';

$categorias = CategoriaController::listarCategorias();
?>

<div class="container">
    <h2>Categorias</h2>

    <h3>Adicionar Categoria</h3>
    <form method="POST" action="../Controllers/CategoriaController.php?action=adicionar">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>
        <button type="submit">Adicionar</button>
    </form>

    <hr>
    <h3>Lista de Categorias</h3>
    <ul>
        <?php foreach($categorias as $cat): ?>
            <li>
                <?= htmlspecialchars($cat['nome']) ?>
                <a href="../Controllers/CategoriaController.php?action=excluir&id=<?= $cat['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
