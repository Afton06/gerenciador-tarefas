<?php
// Inicia a sessão para usar mensagens de feedback
session_start();

// Inclui o arquivo de conexão com o banco de dados
include 'conexao.php';

$tarefa = null;
$categorias = [];
$error_message = "";

// Lógica de processamento do formulário (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validação dos dados
    if (!isset($_POST['id']) || !isset($_POST['titulo']) || empty(trim($_POST['titulo']))) {
        $error_message = "O título da tarefa é obrigatório.";
    } else {
        $id = $_POST['id'];
        $titulo = trim($_POST['titulo']);
        $descricao = $_POST['descricao'];
        $status = $_POST['status'];
        // Se a categoria for vazia, define como NULL para o banco de dados
        $id_categoria = !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : NULL;

        $stmt = $conn->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ?, id_categoria = ? WHERE id = ?");
        $stmt->bind_param("sssii", $titulo, $descricao, $status, $id_categoria, $id);

        if ($stmt->execute()) {
            // Define uma mensagem de sucesso na sessão antes de redirecionar
            $_SESSION['message'] = "Tarefa atualizada com sucesso!";
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Erro ao atualizar: " . $stmt->error;
        }
        $stmt->close();
    }
}
// Lógica para exibir o formulário (GET)
else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Busca a tarefa para edição
    $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $tarefa = $result->fetch_assoc();
    } else {
        $error_message = "Tarefa não encontrada.";
    }
    $stmt->close();
    
    // Busca as categorias para o campo de seleção
    $sql_categorias = "SELECT * FROM categorias ORDER BY nome ASC";
    $result_categorias = $conn->query($sql_categorias);
    if ($result_categorias->num_rows > 0) {
        while($row = $result_categorias->fetch_assoc()) {
            $categorias[] = $row;
        }
    }
} else {
    $error_message = "ID da tarefa não fornecido.";
}

$conn->close();

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
        .error { color: red; margin-bottom: 15px; }
        .link-cancelar { display: inline-block; margin-top: 10px; text-decoration: none; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>

        <?php if ($error_message): ?>
            <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <?php if ($tarefa): ?>
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
                    <option value="" <?php if (is_null($tarefa['id_categoria'])) echo 'selected'; ?>>Sem Categoria</option>
                    <?php
                    foreach ($categorias as $cat) {
                        $selected = ($cat['id'] == $tarefa['id_categoria']) ? 'selected' : '';
                        echo "<option value='" . $cat['id'] . "' " . $selected . ">" . htmlspecialchars($cat['nome']) . "</option>";
                    }
                    ?>
                </select>

                <button type="submit">Salvar Alterações</button>
            </form>
            <a href="index.php" class="link-cancelar">Cancelar</a>
        <?php else: ?>
            <p><a href="index.php">Voltar para a lista de tarefas</a></p>
        <?php endif; ?>
    </div>
</body>
</html>