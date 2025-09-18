<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();

// Buscar tarefas
$sql = "SELECT t.*, c.nome AS categoria_nome 
        FROM tarefas t 
        LEFT JOIN categorias c ON t.id_categoria = c.id
        ORDER BY t.data_criacao DESC";
$stmt = $pdo->query($sql);
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']); ?>!</h1>
    <a href="logout.php">Sair</a> | <a href="categorias.php">Gerenciar Categorias</a>

    <h2>Adicionar Nova Tarefa</h2>
    <form action="adicionar.php" method="POST">
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

    <h2>Lista de Tarefas</h2>
    <table>
        <tr>
            <th>Título</th>
            <th>Descrição</th>
            <th>Categoria</th>
            <th>Status</th>
            <th>Vencimento</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($tarefas as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['titulo']); ?></td>
                <td><?= htmlspecialchars($t['descricao']); ?></td>
                <td><?= htmlspecialchars($t['categoria_nome']); ?></td>
                <td><?= $t['status'] == 'concluida' ? 'Concluída' : 'Pendente'; ?></td>
                <td><?= $t['data_vencimento'] ? date('d/m/Y', strtotime($t['data_vencimento'])) : 'N/A'; ?></td>
                <td>
                    <a href="editar.php?id=<?= $t['id']; ?>">Editar</a> |
                    <a href="excluir.php?id=<?= $t['id']; ?>" onclick="return confirm('Excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>