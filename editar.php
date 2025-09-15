<?php
session_start();
include 'conexao.php';

// Cria uma instância da classe de conexão
$database = new Conexao();

// Obtém a conexão ativa
$conn = $database->getConnection();

$tarefa = null;
$categorias = [];

// Lógica para exibir os dados no formulário
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $tarefa = $result->fetch_assoc();
    }
    $stmt->close();

    // Busca as categorias para o campo de seleção
    $sql_categorias = "SELECT * FROM categorias";
    $result_categorias = $conn->query($sql_categorias);
    if ($result_categorias->num_rows > 0) {
        while($row = $result_categorias->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
}

// Lógica para atualizar os dados após o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $id_categoria = $_POST['id_categoria'];
    $data_vencimento = $_POST['data_vencimento'];

    $stmt = $conn->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, id_categoria = ?, data_vencimento = ? WHERE id = ?");
    $stmt->bind_param("sssiis", $titulo, $descricao, $status, $id_categoria, $data_vencimento, $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Tarefa atualizada com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar: " . $stmt->error;
        header("Location: index.php");
        exit();
    }
    $stmt->close();
}

// O restante do HTML
if ($tarefa) {
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarefa['id']); ?>">
            
            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" required><br><br>

            <label for="descricao">Descrição:</label><br>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea><br><br>

            <label for="status">Status:</label><br>
            <select id="status" name="status">
                <option value="pendente" <?php if ($tarefa['status'] == 'pendente') echo 'selected'; ?>>Pendente</option>
                <option value="concluida" <?php if ($tarefa['status'] == 'concluida') echo 'selected'; ?>>Concluída</option>
            </select><br><br>

            <label for="categoria">Categoria:</label><br>
            <select id="categoria" name="id_categoria">
                <?php
                foreach ($categorias as $cat) {
                    $selected = ($cat['id'] == $tarefa['id_categoria']) ? 'selected' : '';
                    echo "<option value='" . $cat['id'] . "' " . $selected . ">" . htmlspecialchars($cat['nome']) . "</option>";
                }
                ?>
            </select><br><br>
            
            <label for="data_vencimento">Data de Vencimento:</label><br>
            <input type="date" id="data_vencimento" name="data_vencimento" value="<?php echo htmlspecialchars($tarefa['data_vencimento']); ?>"><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>
        <a href="index.php">Cancelar</a>
    </div>
</body>
</html>
<?php
} else {
    echo "Tarefa não encontrada.";
}

// Fecha a conexão com o banco de dados
$database->closeConnection();
?>