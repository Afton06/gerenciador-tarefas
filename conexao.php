<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "gerenciador_tarefas";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>