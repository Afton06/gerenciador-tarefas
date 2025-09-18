<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once '../Models/Tarefa.php';
require_once '../Models/Categoria.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Tarefas</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="container">
    <h1>Tarefas</h1>
    <a href="dashboard.php">Voltar</a> | 
    <a href="../Controllers/UsuarioController.php?action=logout">Sair</a>

    <h2>Adicionar Nova Tarefa</h2>
    <form action="../Controllers/TarefaController.php?action=adicionar" method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" required><br><br>
        <label>Descrição:</label><br>
        <textarea name="descricao"></textarea><br><br>
        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento"><br><br>
        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']); ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <button type="submit">Adicionar</button>
    </form>

    <hr>
    <h2>Lista de Tarefas</h2>
    <table>
        <thead>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Vencimento</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($tarefas): ?>
            <?php foreach ($tarefas as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['titulo']); ?></td>
                    <td><?= htmlspecialchars($t['descricao']); ?></td>
                    <td><?= htmlspecialchars($t['categoria_nome']); ?></td>
                    <td><?= $t['status'] == 'concluida' ? 'Concluída' : 'Pendente'; ?></td>
                    <td><?= $t['data_vencimento'] ? date('d/m/Y', strtotime($t['data_vencimento'])) : 'N/A'; ?></td>
                    <td>
                        <a href="editar_tarefa.php?id=<?= $t['id']; ?>">Editar</a>
                        <a href="../Controllers/TarefaController.php?action=excluir&id=<?= $t['id']; ?>" onclick="return confirm('Excluir?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhuma tarefa encontrada.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>