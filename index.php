<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Define o comando SQL para selecionar todas as tarefas
$sql = "SELECT tarefas.*, categorias.nome AS categoria_nome FROM tarefas LEFT JOIN categorias ON tarefas.id_categoria = categorias.id ORDER BY tarefas.data_criacao DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; }
        form, table { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .acoes a { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>

        <form action="adicionar.php" method="POST">
            <h3>Adicionar Nova Tarefa</h3>
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" required><br><br>
            
            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao"></textarea><br><br>

            <label for="categoria">Categoria:</label><br>
            <select id="categoria" name="id_categoria">
                <?php
                // Busca as categorias do banco de dados para o dropdown
                $sql_categorias = "SELECT * FROM categorias";
                $result_categorias = $conn->query($sql_categorias);
                if ($result_categorias->num_rows > 0) {
                    while($row_cat = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . $row_cat['id'] . "'>" . $row_cat['nome'] . "</option>";
                    }
                }
                ?>
            </select><br><br>

            <button type="submit">Adicionar Tarefa</button>
        </form>

        <hr>

        <h3>Tarefas Pendentes</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['titulo'] . "</td>";
                        echo "<td>" . $row['descricao'] . "</td>";
                        echo "<td>" . $row['categoria_nome'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td class='acoes'>";
                        echo "<a href='editar.php?id=" . $row['id'] . "'>Editar</a>";
                        echo "<a href='excluir.php?id=" . $row['id'] . "'>Excluir</a>";
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

<?php
// Fecha a conexão com o banco de dados
$conn->close();
?>