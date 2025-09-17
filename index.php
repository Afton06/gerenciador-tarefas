<?php
session_start();
include_once 'Tarefa.php';

$tarefaObj = new Tarefa();
$status = isset($_GET['status']) && $_GET['status'] != "" ? $_GET['status'] : null;
$result = $tarefaObj->listar($status);

// Categorias (usamos conexão direta)
include_once 'conexao.php';
$database = new Conexao();
$conn = $database->getConnection();
$sql_categorias = "SELECT * FROM categorias ORDER BY nome ASC";
$result_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . htmlspecialchars($_SESSION['message']) . "</p>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo "<p class='error'>" . htmlspecialchars($_SESSION['error_message']) . "</p>";
            unset($_SESSION['error_message']);
        }
        ?>

        <form action="adicionar.php" method="POST">
            <h3>Adicionar Nova Tarefa</h3>
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" required><br><br>
            
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao" rows="4"></textarea><br><br>

            <label for="data_vencimento">Data de Vencimento:</label><br>
            <input type="date" id="data_vencimento" name="data_vencimento"><br><br>

            <label for="categoria">Categoria:</label><br>
            <select id="categoria" name="id_categoria">
                <option value="">Sem Categoria</option>
                <?php
                if ($result_categorias->num_rows > 0) {
                    while($row_cat = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . $row_cat['id'] . "'>" . htmlspecialchars($row_cat['nome']) . "</option>";
                    }
                }
                ?>
            </select><br><br>

            <button type="submit">Adicionar Tarefa</button>
        </form>

        <hr>

        <div class="filtro-form">
            <form action="index.php" method="GET">
                <label for="status">Filtrar por Status:</label>
                <select id="status" name="status">
                    <option value="">Todas</option>
                    <option value="pendente" <?php if ($status == 'pendente') echo 'selected'; ?>>Pendente</option>
                    <option value="concluida" <?php if ($status == 'concluida') echo 'selected'; ?>>Concluída</option>
                </select>
                <button type="submit">Filtrar</button>
            </form>
        </div>

        <h3>Lista de Tarefas</h3>
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
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $is_concluida = ($row['status'] == 'concluida');
                        echo "<tr class='" . ($is_concluida ? 'tarefa-concluida' : '') . "'>";
                        echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descricao']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['categoria_nome']) . "</td>";
                        
                        echo "<td class='" . ($is_concluida ? 'status-concluida' : 'status-pendente') . "'>";
                        echo ($is_concluida ? 'Concluída' : 'Pendente');
                        echo "</td>";

                        echo "<td>" . ($row['data_vencimento'] ? date('d/m/Y', strtotime($row['data_vencimento'])) : 'N/A') . "</td>";
                        
                        echo "<td class='botoes-acao'>";
                        echo "<a href='editar.php?id=" . $row['id'] . "' class='editar'>Editar</a>";
                        echo "<a href='excluir.php?id=" . $row['id'] . "' class='excluir' onclick='return confirm(\"Tem certeza que deseja excluir esta tarefa?\")'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Nenhuma tarefa encontrada.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>