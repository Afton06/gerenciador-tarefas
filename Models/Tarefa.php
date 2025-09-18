<?php
require_once 'Conexao.php';

class Tarefa {
    public $id;
    public $titulo;
    public $descricao;
    public $status;
    public $data_criacao;
    public $data_vencimento;
    public $id_categoria;
    public $id_usuario;

    public static function listar($id_usuario) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT tarefas.*, categorias.nome AS categoria_nome 
                               FROM tarefas 
                               LEFT JOIN categorias ON tarefas.id_categoria = categorias.id
                               WHERE tarefas.id_usuario = ?
                               ORDER BY tarefas.data_criacao DESC");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function adicionar($titulo, $descricao, $id_categoria, $data_vencimento, $id_usuario) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria, data_vencimento, id_usuario) 
                               VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$titulo, $descricao, $id_categoria, $data_vencimento, $id_usuario]);
    }

    public static function buscar($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function atualizar($id, $titulo, $descricao, $status, $id_categoria, $data_vencimento) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("UPDATE tarefas SET titulo=?, descricao=?, status=?, id_categoria=?, data_vencimento=? WHERE id=?");
        return $stmt->execute([$titulo, $descricao, $status, $id_categoria, $data_vencimento, $id]);
    }

    public static function excluir($id) {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}