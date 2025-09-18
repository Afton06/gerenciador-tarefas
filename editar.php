<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
include 'conexao.php';
$pdo = Conexao::getConexao();
$id_usuario = $_SESSION['usuario_id'];

// buscar tarefa e verificar propriedade
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
    $tarefa = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tarefa) die("Tarefa não encontrada.");
    if ($tarefa['id_usuario'] != $id_usuario) die("Acesso negado."); // protege
}

// atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    // re-check ownership para segurança
    $stmt = $pdo->prepare("SELECT id_usuario FROM tarefas WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || $row['id_usuario'] != $id_usuario) {
        die("Acesso negado.");
    }

    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'] ?: null;
    $status = $_POST['status'];
    $data_vencimento = !empty($_POST['data_vencimento']) ? $_POST['data_vencimento'] : null;
    $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null;

    $stmt = $pdo->prepare("UPDATE tarefas SET titulo=?, descricao=?, status=?, data_vencimento=?, id_categoria=? WHERE id=?");
    $stmt->execute([$titulo, $descricao, $status, $data_vencimento, $id_categoria, $id]);

    header("Location: index.php");
    exit();
}

// categorias para dropdown
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Editar Tarefa</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Editar Tarefa</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?= $tarefa['id'] ?>">
        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"><?= htmlspecialchars($tarefa['descricao']) ?></textarea><br><br>

        <label>Status:</label><br>
        <select name="status">
            <option value="pendente" <?= $tarefa['status']=='pendente'?'selected':'' ?>>Pendente</option>
            <option value="concluida" <?= $tarefa['status']=='concluida'?'selected':'' ?>>Concluída</option>
        </select><br><br>

        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento" value="<?= $tarefa['data_vencimento'] ?>"><br><br>

        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $tarefa['id_categoria']==$cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Salvar</button>
    </form>
    <br><a href="index.php">Voltar</a>
</div>
</body>
</html>