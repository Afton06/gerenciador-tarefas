<?php
class Conexao {
    private static $instancia;

    public static function getConexao() {
        if (!isset(self::$instancia)) {
            self::$instancia = new PDO("mysql:host=localhost;dbname=gerenciador_tarefas;charset=utf8", "root", "");
            self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instancia;
    }
}
?>