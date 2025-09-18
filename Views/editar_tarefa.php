<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../Models/Tarefa.php';
require_once '../Models/Categoria.php';

$tarefa = Tarefa::getById($_GET['id']);
$categorias = Categoria::getAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Editar Tarefa</h1>
    <form action="../Controllers/TarefaController.php?action=editar" method="POST">
        <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']); ?>" required><br><br>
        <label>Descrição:</label><br>
        <textarea name="descricao"><?= htmlspecialchars($tarefa['descricao']); ?></textarea><br><br>
        <label>Status:</label><br>
        <select name="status">
            <option value="pendente" <?= $tarefa['status']=='pendente'?'selected':'' ?>>Pendente</option>
            <option value="concluida" <?= $tarefa['status']=='concluida'?'selected':'' ?>>Concluída</option>
        </select><br><br>
        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $tarefa['id_categoria']==$cat['id']?'selected':'' ?>>
                    <?= htmlspecialchars($cat['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>
        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento" value="<?= $tarefa['data_vencimento']; ?>"><br><br>
        <button type="submit">Salvar</button>
    </form>
    <a href="tarefas.php">Cancelar</a>
</div>
</body>
</html>