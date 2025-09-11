<?php
include 'conexao.php';

$tarefa = null;
$categorias = [];

// Passo 1: Lógica para exibir os dados no formulário
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

// Passo 2: Lógica para atualizar os dados após o envio do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $id_categoria = $_POST['id_categoria'];

    $stmt = $conn->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, id_categoria = ? WHERE id = ?");
    $stmt->bind_param("sssii", $titulo, $descricao, $status, $id_categoria, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

if ($tarefa) {
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; }
        form { margin-top: 20px; }
        label, input, textarea, select, button { display: block; margin-bottom: 10px; width: 100%; }
        input[type="text"], textarea, select { padding: 8px; box-sizing: border-box; }
        button { padding: 10px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>
        <form action="editar.php" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($tarefa['id']); ?>">
            
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($tarefa['titulo']); ?>" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($tarefa['descricao']); ?></textarea>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="pendente" <?php if ($tarefa['status'] == 'pendente') echo 'selected'; ?>>Pendente</option>
                <option value="concluida" <?php if ($tarefa['status'] == 'concluida') echo 'selected'; ?>>Concluída</option>
            </select>

            <label for="categoria">Categoria:</label>
            <select id="categoria" name="id_categoria">
                <?php
                foreach ($categorias as $cat) {
                    $selected = ($cat['id'] == $tarefa['id_categoria']) ? 'selected' : '';
                    echo "<option value='" . $cat['id'] . "' " . $selected . ">" . htmlspecialchars($cat['nome']) . "</option>";
                }
                ?>
            </select>

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
?>