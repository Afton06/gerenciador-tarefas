<?php
class Conexao {
    private static $pdo;

    public static function getConexao() {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO("mysql:host=localhost;dbname=gerenciador_tarefas;charset=utf8", "root", "");
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
