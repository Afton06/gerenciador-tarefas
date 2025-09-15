<?php
// Inicia a sessão para usar mensagens de feedback
session_start();

// Inclui a classe de conexão com o banco de dados
include 'conexao.php';

// Cria uma instância da classe de conexão
$database = new Conexao();

// Obtém a conexão ativa
$conn = $database->getConnection();

// Inicializa as variáveis para a consulta preparada
$where_clause = "";
$bind_params = [];
$bind_types = "";

// Verifica se há um filtro de status na URL
if (isset($_GET['status']) && $_GET['status'] != "") {
    $status_filtro = $_GET['status'];
    // Usa uma consulta preparada para evitar injeção de SQL
    $where_clause = "WHERE tarefas.status = ?";
    $bind_params[] = &$status_filtro;
    $bind_types .= "s";
}

// Define o comando SQL para selecionar todas as tarefas
$sql = "SELECT tarefas.*, categorias.nome AS categoria_nome 
        FROM tarefas 
        LEFT JOIN categorias ON tarefas.id_categoria = categorias.id 
        " . $where_clause . "
        ORDER BY tarefas.data_criacao DESC";

// Prepara a consulta para execução segura
$stmt = $conn->prepare($sql);

// Se houver um filtro de status, vincula o parâmetro
if (!empty($bind_params)) {
    $stmt->bind_param($bind_types, ...$bind_params);
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>

        <?php
        // Exibe mensagens de sucesso ou erro, se existirem
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
                // Preenche o dropdown de categorias
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

<?php
// Fecha a conexão com o banco de dados
$stmt->close();
$conn->close();
?>