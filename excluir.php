<?php
// Inclui o arquivo de conexão com o banco de dados.
// É uma prática recomendada usar 'require_once' para garantir que o arquivo seja incluído apenas uma vez
// e para interromper o script se a conexão for essencial e falhar.
require_once 'conexao.php';

// Verifica se o parâmetro 'id' foi enviado via método GET.
// O 'isset' é fundamental para evitar erros quando a página é acessada sem o ID.
if (isset($_GET['id'])) {
    // Filtra o ID para garantir que seja um número inteiro,
    // o que adiciona uma camada extra de segurança contra entradas inesperadas.
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepara o comando SQL para a exclusão.
    // O uso de '?' é um prepared statement, a forma mais segura de interagir com o banco de dados
    // e prevenir ataques de injeção de SQL.
    $stmt = $conn->prepare("DELETE FROM tarefas WHERE id = ?");

    // Vincula o parâmetro 'id' ao prepared statement.
    // O 'i' indica que o tipo de dado é um inteiro.
    $stmt->bind_param("i", $id);

    // Executa a query de exclusão.
    if ($stmt->execute()) {
        // Redireciona o usuário para a página principal se a exclusão for bem-sucedida.
        // O 'exit()' é importante para garantir que o script pare de ser executado após o redirecionamento.
        header("Location: index.php");
        exit();
    } else {
        // Exibe uma mensagem de erro se a execução da query falhar.
        // O 'error' fornece informações úteis para depuração.
        echo "Erro ao excluir: " . $stmt->error;
    }

    // Fecha o statement e a conexão para liberar os recursos do banco de dados.
    $stmt->close();
    $conn->close();
} else {
    // Mensagem de erro caso o ID não seja fornecido na URL.
    echo "ID da tarefa não especificado.";
}
?>