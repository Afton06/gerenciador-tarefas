<?php
require_once 'header.php';
require_once '../Controllers/TarefaController.php';
require_once '../Models/Categoria.php';

$categorias = Categoria::listar();
$tarefas = TarefaController::listarTarefasUsuario($_SESSION['usuario_id']);
?>

<div class="container">
    <h2>Minhas Tarefas</h2>

    <h3>Adicionar Nova Tarefa</h3>
    <form method="POST" action="../Controllers/TarefaController.php?action=adicionar">
        <label>Título:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"></textarea><br><br>

        <label>Data de Vencimento:</label><br>
        <input type="date" name="data_vencimento"><br><br>

        <label>Categoria:</label><br>
        <select name="id_categoria">
            <option value="">Sem Categoria</option>
            <?php foreach($categorias as $cat) {
                echo "<option value='{$cat['id']}'>".htmlspecialchars($cat['nome'])."</option>";
            } ?>
        </select><br><br>

        <button type="submit">Adicionar</button>
    </form>

    <hr>
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
        <?php if($tarefas) foreach($tarefas as $t): ?>
            <tr class="<?= $t['status']=='concluida'?'tarefa-concluida':'' ?>">
                <td><?= htmlspecialchars($t['titulo']) ?></td>
                <td><?= htmlspecialchars($t['descricao']) ?></td>
                <td><?= htmlspecialchars($t['categoria_nome']) ?></td>
                <td><?= htmlspecialchars($t['status']) ?></td>
                <td><?= $t['data_vencimento'] ? date('d/m/Y', strtotime($t['data_vencimento'])) : 'N/A' ?></td>
                <td>
                    <a href="../Controllers/TarefaController.php?action=editar&id=<?= $t['id'] ?>">Editar</a>
                    <a href="../Controllers/TarefaController.php?action=excluir&id=<?= $t['id'] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>