<?php
require_once 'Conexao.php';

class Categoria {
    public $id;
    public $nome;

    public static function listar() {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function criar($nome) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
        return $stmt->execute([$nome]);
    }

    public static function excluir($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}