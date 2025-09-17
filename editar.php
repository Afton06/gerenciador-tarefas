<?php
session_start();
include_once 'Tarefa.php';
include_once 'conexao.php';

$tarefaObj = new Tarefa();
$database = new Conexao();
$conn = $database->getConnection();

$tarefa = null;
$categorias = [];

if (isset($_GET['id'])) {
    $tarefa = $tarefaObj->buscarPorId($_GET['id']);

    $sql_categorias = "SELECT * FROM categorias";
    $result_categorias = $conn->query($sql_categorias);
    while($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];
    $id_categoria = $_POST['id_categoria'];
    $data_vencimento = $_POST['data_vencimento'];

    if ($tarefaObj->editar($id, $titulo, $descricao, $status, $id_categoria, $data_vencimento)) {
        $_SESSION['message'] = "Tarefa atualizada com sucesso!";
    } else {
        $_SESSION['error_message'] = "Erro ao atualizar tarefa.";
    }

    header("Location: index.php");
    exit();
}

if ($tarefa):
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
else:
    echo "Tarefa não encontrada.";
endif;
?>