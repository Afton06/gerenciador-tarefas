<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();

// Buscar tarefa pelo ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tarefa) {
        die("Tarefa não encontrada!");
    }
}

// Atualizar tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;

    $sql = "UPDATE tarefas 
            SET titulo = ?, descricao = ?, status = ?, data_vencimento = ?, id_categoria = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titulo, $descricao, $status, $data_vencimento, $id_categoria, $id]);

    header("Location: index.php");
    exit();
}

// Buscar categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Editar Tarefa</h1>
    <form method="POST">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']); ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"><?= htmlspecialchars($tarefa['descricao']); ?></textarea><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="pendente" <?= $tarefa['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
            <option value="concluida" <?= $tarefa['status'] === 'concluida' ? 'selected' : ''; ?>>Concluída</option>
        </select><br><br>

        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento" value="<?= $tarefa['data_vencimento']; ?>"><br><br>

        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id']; ?>" <?= $tarefa['id_categoria'] == $cat['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($cat['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Salvar Alterações</button>
    </form>
    <br>
    <a href="index.php">Voltar</a>
</div>
</body>
</html>