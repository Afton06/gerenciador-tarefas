<?php
include_once 'conexao.php';

class Tarefa {
    private $conn;

    public function __construct() {
        $database = new Conexao();
        $this->conn = $database->getConnection();
    }

    public function adicionar($titulo, $descricao, $id_categoria, $data_vencimento) {
        $data_criacao = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("INSERT INTO tarefas (titulo, descricao, id_categoria, data_criacao, data_vencimento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $titulo, $descricao, $id_categoria, $data_criacao, $data_vencimento);
        return $stmt->execute();
    }

    public function editar($id, $titulo, $descricao, $status, $id_categoria, $data_vencimento) {
        $stmt = $this->conn->prepare("UPDATE tarefas SET titulo=?, descricao=?, status=?, id_categoria=?, data_vencimento=? WHERE id=?");
        $stmt->bind_param("sssiis", $titulo, $descricao, $status, $id_categoria, $data_vencimento, $id);
        return $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->conn->prepare("DELETE FROM tarefas WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function listar($status = null) {
        $sql = "SELECT tarefas.*, categorias.nome AS categoria_nome 
                FROM tarefas 
                LEFT JOIN categorias ON tarefas.id_categoria = categorias.id";

        if ($status) {
            $sql .= " WHERE tarefas.status = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $status);
            $stmt->execute();
            return $stmt->get_result();
        } else {
            $sql .= " ORDER BY tarefas.data_criacao DESC";
            return $this->conn->query($sql);
        }
    }

    public function buscarPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tarefas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
