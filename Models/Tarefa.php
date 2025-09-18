<?php
require_once 'Conexao.php';

class Tarefa {
    public static function getAll($status = null) {
        $pdo = Conexao::getConexao();
        if ($status) {
            $stmt = $pdo->prepare("SELECT t.*, c.nome AS categoria_nome FROM tarefas t LEFT JOIN categorias c ON t.id_categoria = c.id WHERE t.status=? ORDER BY t.data_criacao DESC");
            $stmt->execute([$status]);
        } else {
            $stmt = $pdo->query("SELECT t.*, c.nome AS categoria_nome FROM tarefas t LEFT JOIN categorias c ON t.id_categoria = c.id ORDER BY t.data_criacao DESC");
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function add($titulo, $descricao, $id_categoria, $data_vencimento) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria, data_vencimento) VALUES (?,?,?,?)");
        return $stmt->execute([$titulo, $descricao, $id_categoria, $data_vencimento]);
    }

    public static function update($id, $titulo, $descricao, $status, $id_categoria, $data_vencimento) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("UPDATE tarefas SET titulo=?, descricao=?, status=?, id_categoria=?, data_vencimento=? WHERE id=?");
        return $stmt->execute([$titulo, $descricao, $status, $id_categoria, $data_vencimento, $id]);
    }

    public static function delete($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id=?");
        return $stmt->execute([$id]);
    }
}