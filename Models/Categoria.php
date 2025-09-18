<?php
require_once 'Conexao.php';

class Categoria {
    public static function getAll() {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function add($nome) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
        return $stmt->execute([$nome]);
    }

    public static function update($id, $nome) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("UPDATE categorias SET nome=? WHERE id=?");
        return $stmt->execute([$nome, $id]);
    }

    public static function delete($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id=?");
        return $stmt->execute([$id]);
    }
}