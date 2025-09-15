<?php
// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

// Inicializa a cláusula WHERE para o filtro
$where_clause = "";

// Verifica se há um filtro de status na URL
if (isset($_GET['status']) && $_GET['status'] != "") {
    $status_filtro = $_GET['status'];
    // Usa uma consulta preparada para evitar injeção de SQL
    $where_clause = "WHERE tarefas.status = ?";
}

// Define o comando SQL para selecionar todas as tarefas
// LEFT JOIN para incluir tarefas mesmo que não tenham categoria
$sql = "SELECT tarefas.*, categorias.nome AS categoria_nome 
        FROM tarefas 
        LEFT JOIN categorias ON tarefas.id_categoria = categorias.id 
        " . $where_clause . "
        ORDER BY tarefas.data_criacao DESC";

// Prepara a consulta para execução segura
$stmt = $conn->prepare($sql);

// Se houver um filtro de status, bind o parâmetro
if ($where_clause != "") {
    $stmt->bind_param("s", $status_filtro);
}

// Executa a consulta
$stmt->execute();
$result = $stmt->get_result();

// Consulta para buscar as categorias (para o dropdown)
$sql_categorias = "SELECT * FROM categorias ORDER BY nome ASC";
$result_categorias = $conn->query($sql_categorias);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <style>
            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f7f9;
        color: #333;
    }

    .container {
        max-width: 900px;
        margin: auto;
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    h1, h3 {
        color: #2c3e50;
        border-bottom: 2px solid #e74c3c;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    form, .filtro-form {
        margin-bottom: 30px;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #eee;
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
        color: #333;
        font-weight: 600;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .status-concluida {
        color: #28a745;
        font-weight: bold;
    }

    .status-pendente {
        color: #ffc107;
        font-weight: bold;
    }

    .tarefa-concluida {
        text-decoration: line-through;
        color: #888;
    }

    .botoes-acao {
        display: flex;
        gap: 8px;
    }

    .botoes-acao a {
        text-decoration: none;
        padding: 8px 12px;
        border-radius: 5px;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .botoes-acao .editar {
        background-color: #f39c12;
        color: white;
    }

    .botoes-acao .excluir {
        background-color: #e74c3c;
        color: white;
    }
    
    .botoes-acao .editar:hover, .botoes-acao .excluir:hover {
        opacity: 0.9;
    }

    button {
        padding: 10px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2980b9;
    }
</style>
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
                    <option value="pendente" <?php if (isset($_GET['status']) && $_GET['status'] == 'pendente') echo 'selected'; ?>>Pendente</option>
                    <option value="concluida" <?php if (isset($_GET['status']) && $_GET['status'] == 'concluida') echo 'selected'; ?>>Concluída</option>
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
                        
                        // Exibição visual do status
                        echo "<td class='" . ($is_concluida ? 'status-concluida' : 'status-pendente') . "'>";
                        echo ($is_concluida ? 'Concluída' : 'Pendente');
                        echo "</td>";

                        // Formata a data de vencimento
                        echo "<td>" . ($row['data_vencimento'] ? date('d/m/Y', strtotime($row['data_vencimento'])) : 'N/A') . "</td>";
                        
                        echo "<td class='botoes-acao'>";
                        echo "<a href='editar.php?id=" . $row['id'] . "' class='editar'>Editar</a>";
                        // Adiciona o pop-up de confirmação
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

<?php
// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();
?>