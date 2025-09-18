<?php
session_start();
include 'conexao.php';
$pdo = Conexao::getConexao();

// Mensagens e dados do formulário
$error_message = $_SESSION['error_message'] ?? '';
$success_message = $_SESSION['message'] ?? '';
$form_data = $_SESSION['form_data'] ?? [];
unset($_SESSION['error_message'], $_SESSION['message'], $_SESSION['form_data']);

// Filtros
$where = "";
$params = [];
if (!empty($_GET['status'])) {
    $where = "WHERE tarefas.status = ?";
    $params[] = $_GET['status'];
}

// Busca tarefas
$sql = "SELECT tarefas.*, categorias.nome AS categoria_nome 
        FROM tarefas 
        LEFT JOIN categorias ON tarefas.id_categoria = categorias.id
        $where 
        ORDER BY tarefas.data_criacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f7f9;
            color: #333;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        input[type="text"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
            box-sizing: border-box;
            resize: none;
            height: 50px;
            overflow: hidden;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            padding: 12px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        th {
            background-color: #eaf2f8;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .status-concluida { color: #28a745; font-weight: bold; }
        .status-pendente { color: #ffc107; font-weight: bold; }
        .tarefa-concluida { text-decoration: line-through; color: #888; }

        .message { color: green; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }

        .filtro-form { margin-bottom: 20px; }
        .filtro-form select { width: auto; display: inline-block; }
    </style>
</head>
<body>
<div class="container">
    <h1>Gerenciador de Tarefas</h1>

    <?php if ($success_message): ?>
        <p class="message"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <p class="error"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <!-- Formulário de nova tarefa -->
    <form action="adicionar.php" method="POST">
        <h2>Adicionar Nova Tarefa</h2>

        <label>Título:</label><br>
        <input type="text" name="titulo" value="<?= htmlspecialchars($form_data['titulo'] ?? '') ?>" required><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"><?= htmlspecialchars($form_data['descricao'] ?? '') ?></textarea><br>

        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento" value="<?= htmlspecialchars($form_data['data_vencimento'] ?? '') ?>"><br>

        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($form_data['id_categoria']) && $form_data['id_categoria'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Adicionar</button>
    </form>

    <!-- Filtro de status -->
    <div class="filtro-form">
        <form action="index.php" method="GET">
            <label>Filtrar por Status:</label>
            <select name="status">
                <option value="">Todas</option>
                <option value="pendente" <?= (isset($_GET['status']) && $_GET['status'] == 'pendente') ? 'selected' : '' ?>>Pendente</option>
                <option value="concluida" <?= (isset($_GET['status']) && $_GET['status'] == 'concluida') ? 'selected' : '' ?>>Concluída</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>
    </div>

    <!-- Lista de tarefas -->
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
                <tr class="<?= $t['status']=='concluida' ? 'tarefa-concluida' : '' ?>">
                    <td><?= htmlspecialchars($t['titulo']) ?></td>
                    <td><?= htmlspecialchars($t['descricao']) ?></td>
                    <td><?= htmlspecialchars($t['categoria_nome']) ?></td>
                    <td class="<?= $t['status']=='concluida' ? 'status-concluida' : 'status-pendente' ?>">
                        <?= $t['status']=='concluida' ? 'Concluída' : 'Pendente' ?>
                    </td>
                    <td><?= $t['data_vencimento'] ? date('d/m/Y', strtotime($t['data_vencimento'])) : 'N/A' ?></td>
                    <td>
                        <a href="editar.php?id=<?= $t['id'] ?>">Editar</a>
                        <a href="excluir.php?id=<?= $t['id'] ?>" onclick="return confirm('Excluir?')">Excluir</a>
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