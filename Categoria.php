<?php
class Categoria {
    private $id;
    private $nome;

    public function __construct($nome) {
        $this->nome = $nome;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }

    // Setters
    public function setNome($nome) { $this->nome = $nome; }
}
?>