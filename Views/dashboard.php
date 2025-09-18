<?php
require_once 'header.php';
?>

<div class="container">
    <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>
    <p>Use o menu acima para gerenciar suas tarefas e categorias.</p>
</div>