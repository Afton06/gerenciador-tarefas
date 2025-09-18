<?php
class Conexao {
    private static $host = "localhost";
    private static $dbname = "gerenciador_tarefas";
    private static $user = "root";
    private static $pass = "";

    public static function getConexao() {
        try {
            $pdo = new PDO(
                "mysql:host=".self::$host.";dbname=".self::$dbname.";charset=utf8",
                self::$user,
                self::$pass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro de conexão: ".$e->getMessage());
        }
    }
}
